<?php

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('notes index lists notes', function () {
    $note = Note::factory()->create();

    $response = $this->get(route('notes.index'));

    $response->assertOk();
    $response->assertSee($note->title);
});

test('a note can be created', function () {
    $response = $this->post(route('notes.store'), [
        'title' => 'Grocery list',
        'body' => 'Milk, eggs, bread',
    ]);

    $response->assertRedirect(route('notes.index'));
    $this->assertDatabaseHas('notes', [
        'title' => 'Grocery list',
        'body' => 'Milk, eggs, bread',
    ]);
});

test('creating a note requires a title and body', function () {
    $response = $this->post(route('notes.store'), [
        'title' => '',
        'body' => '',
    ]);

    $response->assertSessionHasErrors(['title', 'body']);
});

test('a note can be viewed', function () {
    $note = Note::factory()->create();

    $response = $this->get(route('notes.show', $note));

    $response->assertOk();
    $response->assertSee($note->title);
    $response->assertSee($note->body);
});

test('a note can be updated', function () {
    $note = Note::factory()->create();

    $response = $this->put(route('notes.update', $note), [
        'title' => 'Updated title',
        'body' => 'Updated body',
    ]);

    $response->assertRedirect(route('notes.index'));
    $this->assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated title',
        'body' => 'Updated body',
    ]);
});

test('a note can be deleted', function () {
    $note = Note::factory()->create();

    $response = $this->delete(route('notes.destroy', $note));

    $response->assertRedirect(route('notes.index'));
    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
});
