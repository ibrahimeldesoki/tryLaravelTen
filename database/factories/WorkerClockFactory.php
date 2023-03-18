<?php

namespace Database\Factories;

use App\Models\WorkerClock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkerClock>
 */
class WorkerClockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->unixTime();
        return [
            'time' =>  $faker->unixTime(),
            'type' => WorkerClock::TYPE_IN,
        ];
    }
}
