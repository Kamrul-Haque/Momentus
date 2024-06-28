<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence($this->faker->numberBetween(2, 5)),
            'description' => $this->faker->paragraph(),
            'start_at' => Carbon::now()->subDays($this->faker->numberBetween(1, 15))->toDateString(),
            'end_at' => Carbon::now()->addDays($this->faker->numberBetween(1, 15))->toDateString(),
            'status' => $this->faker->randomElement(EventStatus::cases())->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by_id' => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
