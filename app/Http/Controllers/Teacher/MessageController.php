<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Message;
use App\Models\User;
use App\Models\Student;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        $messages = Message::where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('teacher.messages.index', compact('teacher', 'messages'));
    }

    public function create()
    {
        $teacher = $this->getTeacher();
        
        // Get students from teacher's classes
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        $students = Student::whereIn('class_id', $classIds)
            ->with('user')
            ->get();
        
        // Get all teachers and admins
        $users = User::role(['Teacher', 'Admin', 'Super Admin'])->get();
        
        return view('teacher.messages.create', compact('teacher', 'students', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'nullable|in:low,normal,high'
        ]);
        
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'] ?? 'normal',
            'status' => 'sent'
        ]);
        
        return redirect()->route('teacher.messages.index')
            ->with('success', 'Message sent successfully');
    }

    public function show($id)
    {
        $teacher = $this->getTeacher();
        
        $message = Message::where(function($query) {
            $query->where('sender_id', Auth::id())
                ->orWhere('recipient_id', Auth::id());
        })->findOrFail($id);
        
        // Mark as read if recipient
        if ($message->recipient_id == Auth::id() && $message->status == 'sent') {
            $message->update(['status' => 'read', 'read_at' => now()]);
        }
        
        return view('teacher.messages.show', compact('teacher', 'message'));
    }
}
