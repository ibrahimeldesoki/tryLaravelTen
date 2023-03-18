<?php

namespace Tests\Feature;

use App\Models\WorkerClock;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_in_api_for_worker(): void
    {
        $user = UserFactory::new()->create();
        $time = strtotime(now()->format('Y-m-d H:i:s'));

        $this->actingAs($user)
            ->postJson('/api/worker/clock-in', [
                'worker_id' => $user->id,
                'time' => $time,
                'latitude' => '30.04934232952842',
                'longitude' => '31.240346675156886'
            ])
            ->assertJson(['message' => 'success Clock in'])
            ->assertStatus(200);

        $this->assertDatabaseHas('worker_clocks', [
            'worker_id' => $user->id,
            'time' => $time,
            'type' => WorkerClock::TYPE_IN
        ]);
    }

    public function test_missed_latitude_longitude_clocks_in_api_for_worker(): void
    {
        $user = UserFactory::new()->create();

         $this->actingAs($user)
            ->postJson('/api/worker/clock-in', [
                'worker_id' => $user->id,
                'time' => strtotime(now()->format('Y-m-d H:i:s')),
            ])
            ->assertStatus(422)
            ->assertInvalid(['latitude', 'longitude']);

        $this->assertDatabaseCount('worker_clocks', 0);
    }

    public function test_out_of_area_clocks_in_api_for_worker(): void
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user)
            ->postJson('/api/worker/clock-in', [
                'worker_id' => $user->id,
                'time' => strtotime(now()->format('Y-m-d H:i:s')),
                'latitude' => '29.42968824835785',
                'longitude' => '41.97154610755517'
            ])
            ->assertStatus(422)
            ->assertJson(['message' => 'outside permitted area']);

        $this->assertDatabaseCount('worker_clocks', 0);
    }
}
