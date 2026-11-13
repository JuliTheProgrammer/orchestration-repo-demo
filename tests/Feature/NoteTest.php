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
