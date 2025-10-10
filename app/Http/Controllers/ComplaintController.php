<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\ComplaintNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'assignedTo']);

        // Admin sees all complaints, users see only their own
        if (!Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('complaint_number', 'like', '%' . $request->search . '%')
                    ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->latest()->paginate(20);

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:academic,administrative,facility,transport,discipline,fee,teacher,other',
            'priority' => 'required|in:low,medium,high,critical',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint = Complaint::create($validated);

        // Notify admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ComplaintNotification($complaint, 'created'));
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint registered successfully! Complaint Number: ' . $complaint->complaint_number);
    }

    public function show(Complaint $complaint)
    {
        // Check authorization
        if (!Auth::user()->hasRole(['Super Admin', 'Admin']) && $complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this complaint.');
        }

        $complaint->load(['user', 'assignedTo', 'resolvedBy']);

        return view('complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        // Only user can edit their own complaint if it's open
        if ($complaint->user_id !== Auth::id() || !$complaint->isOpen()) {
            abort(403);
        }

        return view('complaints.edit', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        // Only user can update their own complaint if it's open
        if ($complaint->user_id !== Auth::id() || !$complaint->isOpen()) {
            abort(403);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:academic,administrative,facility,transport,discipline,fee,teacher,other',
            'priority' => 'required|in:low,medium,high,critical',
            'attachment' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($complaint->attachment) {
                Storage::disk('public')->delete($complaint->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint->update($validated);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully!');
    }

    public function assign(Request $request, Complaint $complaint)
    {
        $this->authorize('manage', Complaint::class);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $complaint->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress',
        ]);

        // Notify assigned user
        $assignedUser = User::find($validated['assigned_to']);
        $assignedUser->notify(new ComplaintNotification($complaint, 'assigned'));

        return redirect()->back()->with('success', 'Complaint assigned successfully!');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $this->authorize('manage', Complaint::class);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,rejected',
            'admin_response' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved') {
            $updateData['admin_response'] = $validated['admin_response'];
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = Auth::id();
        }

        $complaint->update($updateData);

        // Notify user
        $complaint->user->notify(new ComplaintNotification($complaint, 'updated'));

        return redirect()->back()->with('success', 'Complaint status updated successfully!');
    }

    public function resolve(Request $request, Complaint $complaint)
    {
        $this->authorize('manage', Complaint::class);

        $validated = $request->validate([
            'admin_response' => 'required|string',
        ]);

        $complaint->markAsResolved($validated['admin_response'], Auth::id());

        // Notify user
        $complaint->user->notify(new ComplaintNotification($complaint, 'resolved'));

        return redirect()->back()->with('success', 'Complaint resolved successfully!');
    }

    public function submitFeedback(Request $request, Complaint $complaint)
    {
        // Only complaint owner can submit feedback
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        $complaint->update([
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'],
            'status' => 'closed',
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function destroy(Complaint $complaint)
    {
        $this->authorize('manage', Complaint::class);

        if ($complaint->attachment) {
            Storage::disk('public')->delete($complaint->attachment);
        }

        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully!');
    }

    public function myComplaints()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('complaints.my-complaints', compact('complaints'));
    }

    public function statistics()
    {
        $this->authorize('manage', Complaint::class);

        $stats = [
            'total' => Complaint::count(),
            'open' => Complaint::open()->count(),
            'in_progress' => Complaint::inProgress()->count(),
            'resolved' => Complaint::resolved()->count(),
            'closed' => Complaint::closed()->count(),
            'by_category' => Complaint::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'by_priority' => Complaint::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'avg_rating' => Complaint::whereNotNull('rating')->avg('rating'),
        ];

        return view('complaints.statistics', compact('stats'));
    }
}
