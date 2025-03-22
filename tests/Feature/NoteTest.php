<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_inertia_response_for_notes()
    {
        $user = User::factory()->create();
        Note::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('notes.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) =>
            $page->component('Notes')
                ->has('notes', 3)
            );
    }

    /** @test */
    public function it_creates_a_note()
    {
        $user = User::factory()->create();
        $data = ['title' => 'Test Note', 'content' => 'This is a test', 'user_id' => $user->id];

        $this->actingAs($user)
            ->post(route('notes.store'), $data)
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseHas('notes', ['title' => 'Test Note']);
    }

    /** @test */
    public function it_deletes_a_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('notes.destroy', $note))
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    /** @test */
    public function it_updates_a_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put(route('notes.update', $note), ['title' => 'Updated Title', 'content' => 'Updated Content'])
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseHas('notes', ['title' => 'Updated Title']);
    }

    /** @test */
    public function it_fails_to_create_a_note_without_required_fields()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('notes.store'), [])
            ->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function it_increments_note_views()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('notes.views', $note))
            ->assertStatus(200);

        $this->assertDatabaseHas('notes', ['id' => $note->id, 'views' => 1]);
    }
}
