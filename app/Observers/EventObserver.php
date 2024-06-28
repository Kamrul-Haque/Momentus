<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\GenerateUniqueIdService;

class EventObserver
{
    /**
     * Handle the Event "creating" event.
     */
    public function creating(Event $event): void
    {
        $event->reminder_id = GenerateUniqueIdService::generate('EVT');
    }

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $owner_id = $event->created_by_id;

        $event->users()->sync($owner_id, false);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $owner_id = $event->created_by_id;

        $event->users()->sync($owner_id, false);
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
