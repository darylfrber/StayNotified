<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_inertia_response_for_events()
    {
        $user = User::factory()->create();
        Event::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('events.show'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
            $page->component('Calendar')
                ->has('events', 2)
            );
    }

    /** @test */
    public function it_creates_an_event()
    {
        $user = User::factory()->create();
        $data = [
            'title' => 'Meeting',
            'date' => now()->addDay()->toDateString(),
            'time' => '10:00',
            'confirmed' => true,
            'user_id' => $user->id
        ];

        $this->actingAs($user)
            ->post(route('events.store'), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('events', ['title' => 'Meeting']);
    }

    /** @test */
    public function it_deletes_an_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('events.destroy', $event))
            ->assertRedirect();

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /** @test */
    public function it_updates_an_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('events.update', $event), ['title' => 'Updated Event', 'date' => now()->addDays(2)->toDateString()])
            ->assertRedirect();

        $this->assertDatabaseHas('events', ['title' => 'Updated Event']);
    }

    /** @test */
    public function it_fails_to_create_an_event_without_required_fields()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('events.store'), [])
            ->assertSessionHasErrors(['title', 'date']);
    }
}
