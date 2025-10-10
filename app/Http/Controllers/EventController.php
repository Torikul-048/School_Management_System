<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator');

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->published();
        }

        if ($request->filled('filter')) {
            match($request->filter) {
                'upcoming' => $query->upcoming(),
                'ongoing' => $query->ongoing(),
                'past' => $query->past(),
                default => null,
            };
        }

        $events = $query->orderBy('start_date')->paginate(20);

        return view('events.index', compact('events'));
    }

    public function calendar()
    {
        $events = Event::published()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d'),
                'end' => $event->end_date->addDay()->format('Y-m-d'),
                'color' => $this->getEventColor($event->event_type),
                'url' => route('events.show', $event->id),
            ];
        });

        return view('events.calendar', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:academic,sports,cultural,holiday,exam,meeting,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'target_audience' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_public' => 'boolean',
            'send_notification' => 'boolean',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $validated['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        // Send notifications
        if ($validated['send_notification'] && $validated['status'] === 'published') {
            $this->sendEventNotifications($event, 'created');
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:academic,sports,cultural,holiday,exam,meeting,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'target_audience' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_public' => 'boolean',
            'send_notification' => 'boolean',
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        // Send notifications if status changed
        if ($event->wasChanged('status') && $validated['send_notification']) {
            $type = $validated['status'] === 'cancelled' ? 'cancelled' : 'updated';
            $this->sendEventNotifications($event, $type);
        }

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    public function upcoming()
    {
        $events = Event::upcoming()->with('creator')->paginate(20);
        return view('events.upcoming', compact('events'));
    }

    protected function sendEventNotifications($event, $type = 'created')
    {
        $users = $this->getTargetUsers($event->target_audience);
        
        if ($users->count() > 0) {
            Notification::send($users, new EventNotification($event, $type));
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

        return $query->get();
    }

    protected function getEventColor($type)
    {
        return match($type) {
            'academic' => '#3788d8',
            'sports' => '#28a745',
            'cultural' => '#ffc107',
            'holiday' => '#dc3545',
            'exam' => '#fd7e14',
            'meeting' => '#6f42c1',
            default => '#6c757d',
        };
    }
}
