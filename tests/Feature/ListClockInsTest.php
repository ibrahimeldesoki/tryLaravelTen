<?php

namespace Tests\Feature;

use App\Models\WorkerClock;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ListClockInsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_list_clocks_ins_of_worker(): void
    {
        $user = UserFactory::new()->create();
        WorkerClock::factory()
            ->count(4)
            ->for($user)
            ->create();

        $this->actingAs($user)
            ->getJson('/api/worker/clock-ins?worker_id=' . $user->id)
            ->assertStatus(200)
            ->assertJsonFragment(['worker_id' => $user->id])
            ->assertJsonCount(4, 'clocks.data');
    }
}
