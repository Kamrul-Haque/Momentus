<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Http\Requests\EventRequest;
use App\Mail\EventAssignedMail;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->hasRoleOrHigher(Role::SUPER_ADMIN->value) ? null : auth()->user()->id;
        $search = Str::length($request->search) > 2 ? $request->search : null;
        $status = $request->status === 'all' ? null : $request->status;

        return inertia('Events/Index', [
            'events' => Event::withTrashed()
                             ->with('users')
                             ->where(function ($query) use ($userId) {
                                 $query->when($userId, function ($query) use ($userId) {
                                     $query->whereHas('users', function ($query) use ($userId) {
                                         $query->where('user_id', $userId);
                                     });
                                 });
                             })
                             ->where(function ($query) use ($search) {
                                 $query->where('title', 'LIKE', "%{$search}%");
                             })
                             ->when($status == 'archived', function ($query) {
                                 $query->whereNot('deleted_at', null);
                             })
                             ->when($status == 'upcoming', function ($query) {
                                 $query->where('start_at', '>', now())
                                       ->where('deleted_at', null);
                             })
                             ->when($status == 'completed', function ($query) {
                                 $query->where('end_at', '<=', now())
                                       ->where('deleted_at', null);
                             })
                             ->when($request->sortBy, function ($query, $sortBy) {
                                 $query->orderBy($sortBy, request()->boolean('sortDesc') ? 'desc' : 'asc');
                             }, function ($query) {
                                 $query->orderBy('id', 'desc');
                             })
                             ->paginate($request->perPage)
                             ->withQueryString(),
            'filters' => [
                'page' => $request->page,
                'search' => $request->search,
                'status' => $request->status,
                'sortBy' => $request->sortBy,
                'sortDesc' => $request->sortDesc,
                'perPage' => $request->perPage,
            ],
            'statuses' => EventStatus::names()
        ]);
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
        $validated['users'][] = auth()->user()->id;

        $event = Event::create(Arr::except($validated, 'users'));

        if (sizeof($validated['users']))
        {
            if ($event->users()->sync($validated['users']))
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
    public function show(string $event)
    {
        $event = Event::withTrashed()
                      ->with('users')
                      ->where('reminder_id', $event)
                      ->firstOrFail();

        Gate::authorize('view', $event);

        return inertia('Events/Show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);

        $users = User::whereNot('id', auth()->id())->get();
        $selected_users = $event->users()->whereNot('id', auth()->user()->id)->pluck('id');

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
        Gate::authorize('update', $event);

        $validated = $request->validated();

        $validated['start_at'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_at'] = isset($validated['end_date'])
            ? $validated['end_date'] . ' ' . $validated['end_time']
            : null;
        $validated['status'] = Carbon::create($validated['start_date'])->lessThan(today())
            ? EventStatus::COMPLETED->value
            : EventStatus::UPCOMING->value;
        $users = $event->users;
        $validated['users'][] = auth()->user()->id;

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
        Gate::authorize('delete', $event);

        $event->delete();

        return back()->with('success', 'Event deactivated successfully');
    }

    /**
     * Restore the specified event.
     */
    public function restore($event)
    {
        $event = Event::onlyTrashed()->find($event);

        Gate::authorize('restore', $event);

        $event->restore();

        return back()->with('success', 'Event reactivated successfully');
    }

    /**
     * Delete the specified event from database.
     */
    public function delete($event)
    {
        $event = Event::onlyTrashed()->find($event);

        Gate::authorize('forceDelete', $event);

        $event->forceDelete();

        return to_route('events.index')->with('success', 'Event deleted successfully');
    }
}
