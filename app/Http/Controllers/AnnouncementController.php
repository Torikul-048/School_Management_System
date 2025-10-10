<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index(Request $request)
    {
        $query = Announcement::with('creator');

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->active();
        }

        $announcements = $query->latest()->paginate(20);
        $pinnedAnnouncements = Announcement::active()->pinned()->latest()->get();

        return view('announcements.index', compact('announcements', 'pinnedAnnouncements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|array',
            'priority' => 'required|in:low,normal,high,urgent',
            'attachment' => 'nullable|file|max:10240',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'is_pinned' => 'boolean',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement = Announcement::create($validated);

        // Send notifications
        if ($validated['status'] === 'active') {
            $this->sendNotifications($announcement);
        }

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully!');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|array',
            'priority' => 'required|in:low,normal,high,urgent',
            'attachment' => 'nullable|file|max:10240',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'is_pinned' => 'boolean',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $wasActive = $announcement->status === 'active';
        $announcement->update($validated);

        // Send notifications if newly activated
        if (!$wasActive && $validated['status'] === 'active') {
            $this->sendNotifications($announcement);
        }

        return redirect()->route('announcements.show', $announcement)->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully!');
    }

    public function pin(Announcement $announcement)
    {
        $announcement->update(['is_pinned' => true]);
        return redirect()->back()->with('success', 'Announcement pinned successfully!');
    }

    public function unpin(Announcement $announcement)
    {
        $announcement->update(['is_pinned' => false]);
        return redirect()->back()->with('success', 'Announcement unpinned successfully!');
    }

    protected function sendNotifications($announcement)
    {
        $users = $this->getTargetUsers($announcement->target_audience);

        if ($users->count() === 0) {
            return;
        }

        // Send email notifications
        if ($announcement->send_email) {
            Notification::send($users, new AnnouncementNotification($announcement));
        }

        // Send SMS notifications
        if ($announcement->send_sms) {
            $message = $announcement->title . "\n" . substr($announcement->content, 0, 140);
            
            foreach ($users as $user) {
                if ($user->phone) {
                    $this->smsService->send($user->phone, $message, $user->id);
                }
            }
        }
    }

    protected function getTargetUsers($targetAudience)
    {
        if (!$targetAudience) {
            return User::where('status', 'active')->get();
        }

        $query = User::where('status', 'active');

        if (isset($targetAudience['roles']) && is_array($targetAudience['roles'])) {
            $query->whereHas('roles', function ($q) use ($targetAudience) {
                $q->whereIn('name', $targetAudience['roles']);
            });
        }

        if (isset($targetAudience['classes']) && is_array($targetAudience['classes'])) {
            $query->orWhereHas('student', function ($q) use ($targetAudience) {
                $q->whereIn('class_id', $targetAudience['classes']);
            });
        }

        return $query->get();
    }
}
