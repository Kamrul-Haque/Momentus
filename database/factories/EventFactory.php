<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start_at = Carbon::now()->subDays(10)->addDays($this->faker->numberBetween(1, 30));

        return [
            'title' => $this->faker->sentence($this->faker->numberBetween(2, 5)),
            'description' => $this->faker->paragraph(),
            'start_at' => $start_at,
            'end_at' => Carbon::parse($start_at)->addDays($this->faker->numberBetween(1, 10)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by_id' => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
