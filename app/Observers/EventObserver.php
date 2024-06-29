<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\GenerateUniqueIdService;
use Carbon\Carbon;

class EventObserver
{
    /**
     * Handle the Event "creating" event.
     */
    public function creating(Event $event): void
    {
        $event->reminder_id = GenerateUniqueIdService::generate('EVT');

        if ($event->end_at)
            $event->duration = Carbon::create($event->getAttributes()['start_at'])
                                     ->diffInMinutes($event->getAttributes()['end_at']);
        else
            $event->duration = null;
    }

    /**
     * Handle the Event "updating" event.
     */
    public function updating(Event $event): void
    {
        if ($event->end_at)
            $event->duration = Carbon::create($event->getAttributes()['start_at'])
                                     ->diffInMinutes($event->getAttributes()['end_at']);
        else
            $event->duration = null;
    }
}
