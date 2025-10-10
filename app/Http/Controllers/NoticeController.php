<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\User;
use App\Notifications\NoticeNotification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class NoticeController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index(Request $request)
    {
        $query = Notice::with('creator');

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->published();
        }

        $notices = $query->latest('publish_date')->paginate(20);
        $pinnedNotices = Notice::published()->pinned()->latest()->get();

        return view('notices.index', compact('notices', 'pinnedNotices'));
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_type' => 'required|in:general,urgent,academic,exam,fee,holiday,event,other',
            'priority' => 'required|in:low,normal,high,urgent',
            'target_audience' => 'required|array',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'attachment' => 'nullable|file|max:10240',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'is_pinned' => 'boolean',
            'status' => 'required|in:draft,published',
        ]);

        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
        }

        $notice = Notice::create($validated);

        // Send notifications
        if ($validated['status'] === 'published') {
            $this->sendNotifications($notice);
        }

        return redirect()->route('notices.index')->with('success', 'Notice created successfully!');
    }

    public function show(Notice $notice)
    {
        $notice->load('creator');
        return view('notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_type' => 'required|in:general,urgent,academic,exam,fee,holiday,event,other',
            'priority' => 'required|in:low,normal,high,urgent',
            'target_audience' => 'required|array',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'attachment' => 'nullable|file|max:10240',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'is_pinned' => 'boolean',
            'status' => 'required|in:draft,published,expired,archived',
        ]);

        if ($request->hasFile('attachment')) {
            if ($notice->attachment) {
                Storage::disk('public')->delete($notice->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
        }

        $wasPublished = $notice->status === 'published';
        $notice->update($validated);

        // Send notifications if newly published
        if (!$wasPublished && $validated['status'] === 'published') {
            $this->sendNotifications($notice);
        }

        return redirect()->route('notices.show', $notice)->with('success', 'Notice updated successfully!');
    }

    public function destroy(Notice $notice)
    {
        if ($notice->attachment) {
            Storage::disk('public')->delete($notice->attachment);
        }

        $notice->delete();

        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully!');
    }

    public function pin(Notice $notice)
    {
        $notice->update(['is_pinned' => true]);
        return redirect()->back()->with('success', 'Notice pinned successfully!');
    }

    public function unpin(Notice $notice)
    {
        $notice->update(['is_pinned' => false]);
        return redirect()->back()->with('success', 'Notice unpinned successfully!');
    }

    public function archive(Notice $notice)
    {
        $notice->update(['status' => 'archived']);
        return redirect()->back()->with('success', 'Notice archived successfully!');
    }

    protected function sendNotifications($notice)
    {
        $users = $this->getTargetUsers($notice->target_audience);

        if ($users->count() === 0) {
            return;
        }

        // Send email notifications
        if ($notice->send_email) {
            Notification::send($users, new NoticeNotification($notice));
        }

        // Send SMS notifications
        if ($notice->send_sms) {
            $message = $notice->title . "\n" . substr($notice->content, 0, 140);
            
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
