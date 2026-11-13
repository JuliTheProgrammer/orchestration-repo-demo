<?php

use App\Models\User;
use App\Native\AccountScreen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

it('registers a new account and logs in', function () {
    $screen = new AccountScreen;
    $screen->isRegistering = true;
    $screen->name = 'Jane';
    $screen->email = 'jane@example.com';
    $screen->password = 'secret-password';

    $screen->submit();

    expect(User::where('email', 'jane@example.com')->exists())->toBeTrue()
        ->and(Auth::check())->toBeTrue();
});

it('logs in an existing user and rejects bad passwords', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    $screen = new AccountScreen;
    $screen->email = $user->email;
    $screen->password = 'wrong';

    expect(fn () => $screen->submit())->toThrow(ValidationException::class);

    $screen->password = 'secret-password';
    $screen->submit();

    expect(Auth::id())->toBe($user->id);
});
