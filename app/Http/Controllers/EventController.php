<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Mail\EventAssignedMail;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->paginate();

        return inertia('Events/Index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return inertia('Events/Form', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();

        $validated['start_at'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_at'] = isset($validated['end_date'])
            ? $validated['end_date'] . ' ' . $validated['end_time']
            : null;
        $validated['status'] = Carbon::create($validated['start_date'])->lessThan(today())
            ? EventStatus::COMPLETED->value
            : EventStatus::UPCOMING->value;

        $event = Event::create(Arr::except($validated, 'users'));

        if (sizeof($validated['users']))
        {
            if ($event->users()->sync($validated['users'], false))
            {
                try
                {
                    foreach ($event->users as $user)
                    {
                        Mail::to($user->email)->send(new EventAssignedMail($event, $user->name));
                    }
                }
                catch (\Exception $exception)
                {
                    return back()->with('error', $exception->getMessage());
                }
            }
        }

        return to_route('events.index')->with('success', 'Event created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return inertia('Events/Show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $selected_users = $event->users()->pluck('id');

        return inertia('Events/Form', [
            'event' => $event,
            'users' => $users,
            'selected_users' => $selected_users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $validated['start_at'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_at'] = isset($validated['end_date'])
            ? $validated['end_date'] . ' ' . $validated['end_time']
            : null;
        $validated['status'] = Carbon::create($validated['start_date'])->lessThan(today())
            ? EventStatus::COMPLETED->value
            : EventStatus::UPCOMING->value;
        $users = $event->users;

        $event->update(Arr::except($validated, 'users'));

        if (sizeof($validated['users']))
        {
            $event->users()->sync($validated['users']);

            try
            {
                foreach ($validated['users'] as $user)
                {
                    if (!$users->contains($user))
                    {
                        $user = User::find($user);

                        Mail::to($user->email)->send(new EventAssignedMail($event, $user->name));
                    }
                }
            }
            catch (\Exception $exception)
            {
                return back()->with('error', $exception->getMessage());
            }
        }
        else
            $event->users()->detach();

        return to_route('events.show', $event)->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return back()->with('success', 'Event deactivated successfully');
    }

    /**
     * Restore the specified event.
     */
    public function restore($event)
    {
        Event::onlyTrashed()->find($event)->restore();

        return back()->with('success', 'Event reactivated successfully');
    }

    /**
     * Delete the specified event from database.
     */
    public function delete($event)
    {
        Event::onlyTrashed()->find($event)->forceDelete();

        return to_route('users.index')->with('success', 'Event deleted successfully');
    }
}
