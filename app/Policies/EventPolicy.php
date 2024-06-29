<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $event->users->contains($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): Response
    {
        return $event->is_owner
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        return $event->is_owner
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): Response
    {
        return $event->is_owner
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): Response
    {
        return $event->is_owner
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }
}
