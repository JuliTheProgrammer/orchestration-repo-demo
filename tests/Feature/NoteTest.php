<?php

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a note with fillable attributes', function () {
    $note = Note::create(['title' => 'Groceries', 'body' => 'Milk and eggs']);

    expect($note->exists)->toBeTrue()
        ->and($note->title)->toBe('Groceries')
        ->and($note->body)->toBe('Milk and eggs');
});

it('creates notes with the factory', function () {
    Note::factory()->count(3)->create();

    expect(Note::count())->toBe(3);
});

it('updates and deletes a note', function () {
    $note = Note::factory()->create();

    $note->update(['title' => 'Updated']);
    expect($note->fresh()->title)->toBe('Updated');

    $note->delete();
    expect(Note::count())->toBe(0);
});

it('generates a title and body from the factory', function () {
    $note = Note::factory()->create();

    expect($note->title)->toBeString()->not->toBeEmpty()
        ->and($note->body)->toBeString()->not->toBeEmpty();
});

it('allows factory attributes to be overridden', function () {
    $note = Note::factory()->create(['title' => 'Custom']);

    expect($note->title)->toBe('Custom');
});

it('does not mass assign guarded attributes', function () {
    $note = Note::create(['title' => 'A', 'body' => 'B', 'id' => 999]);

    expect($note->id)->not->toBe(999);
});

it('persists notes to the database', function () {
    Note::create(['title' => 'Saved', 'body' => 'Body']);

    $this->assertDatabaseHas('notes', ['title' => 'Saved', 'body' => 'Body']);
});

it('counts each reaction type on a note', function () {
    $note = Note::factory()->create();

    $note->react('👍');
    $note->react('👍');
    $note->react('🎉');
    $note->react('invalid');

    expect($note->fresh()->reactions)->toBe(['👍' => 2, '🎉' => 1]);
});

it('removes a deleted note from the database', function () {
    $note = Note::factory()->create();
    $note->delete();

    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
});

it('logs the user out from the notes screen', function () {
    $this->actingAs(App\Models\User::factory()->create());

    (new App\Native\NotesIndexScreen)->logout();

    $this->assertGuest();
});
