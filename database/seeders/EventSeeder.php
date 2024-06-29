<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::factory(100)
             ->create()
             ->each(function ($event) {
                 $event->users()->sync($event->created_by_id, false);

                 foreach (range(0, rand(0, 5)) as $index)
                 {
                     $userId = User::inRandomOrder()->first()->id;

                     $event->users()->sync($userId, false);
                 }
             });
    }
}
