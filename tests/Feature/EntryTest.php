<?php

use App\Models\Entry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an entry via the factory', function () {
    $entry = Entry::factory()->create();

    expect($entry->exists)->toBeTrue()
        ->and(Entry::count())->toBe(1);
});
