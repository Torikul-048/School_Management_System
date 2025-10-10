<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'inbox');
        $userId = Auth::id();

        $messages = match($tab) {
            'sent' => Message::outbox($userId)->with(['receiver', 'sender']),
            'archived' => Message::archived($userId)->with(['receiver', 'sender']),
            default => Message::inbox($userId)->with(['receiver', 'sender']),
        };

        if ($request->filled('search')) {
            $messages->where(function ($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $messages = $messages->latest()->paginate(20);
        $unreadCount = Message::inbox($userId)->unread()->count();

        return view('messages.index', compact('messages', 'tab', 'unreadCount'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $validated['sender_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('messages', 'public');
        }

        $message = Message::create($validated);

        // Send notification
        $receiver = User::find($validated['receiver_id']);
        $receiver->notify(new MessageNotification($message));

        return redirect()->route('messages.index', ['tab' => 'sent'])
            ->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        // Check authorization
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this message.');
        }

        // Mark as read if receiver is viewing
        if ($message->receiver_id === Auth::id() && !$message->is_read) {
            $message->markAsRead();
        }

        $message->load(['sender', 'receiver', 'replies.sender']);

        return view('messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

        // Determine sender and receiver
        $senderId = Auth::id();
        $receiverId = ($message->sender_id === $senderId) ? $message->receiver_id : $message->sender_id;

        $validated['sender_id'] = $senderId;
        $validated['receiver_id'] = $receiverId;
        $validated['subject'] = 'Re: ' . $message->subject;
        $validated['priority'] = $message->priority;
        $validated['parent_id'] = $message->id;

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('messages', 'public');
        }

        $reply = Message::create($validated);

        // Send notification
        $receiver = User::find($receiverId);
        $receiver->notify(new MessageNotification($reply));

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    public function archive(Message $message)
    {
        // Check authorization
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message->update(['status' => 'archived']);

        return redirect()->back()->with('success', 'Message archived successfully!');
    }

    public function destroy(Message $message)
    {
        // Check authorization
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403);
        }

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message deleted successfully!');
    }

    public function markAllAsRead()
    {
        Message::inbox(Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All messages marked as read!');
    }

    public function compose(Request $request)
    {
        $receiverId = $request->input('to');
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedUser = $receiverId ? User::find($receiverId) : null;

        return view('messages.compose', compact('users', 'selectedUser'));
    }
}
