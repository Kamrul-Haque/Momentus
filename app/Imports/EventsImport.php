<?php

namespace App\Imports;

use App\Models\Event;
use App\Services\GenerateUniqueIdService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\PersistRelations;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EventsImport implements ToModel, PersistRelations, WithStartRow, withChunkReading, ShouldQueue
{
    protected int $authUserId;

    public function __construct(int $authUserId)
    {
        $this->authUserId = $authUserId;
    }

    /**
     * @param array $row
     *
     * @return Model|Event|null
     */
    public function model(array $row): Model|Event|null
    {
        $event = Event::firstOrCreate([
            'title' => $row[0],
            'reminder_id' => GenerateUniqueIdService::generate('EVT'),
            'start_at' => $row[2] . $row[1],
            'end_at' => $row[4] . $row[3],
            'description' => $row[5],
            'created_by_id' => $this->authUserId,
        ]);

        $event->users()->sync([$this->authUserId], false);

        return $event;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 50;
    }
}
