<?php

use App\Models\Entry;

it('creates an entry via the factory', function () {
    $entry = Entry::factory()->create();

    expect($entry->exists)->toBeTrue()
        ->and(Entry::count())->toBe(1);
});
