<?php

use App\Models\User;
use App\Native\LoginScreen;
use App\Native\NotesIndexScreen;
use App\Native\RegisterScreen;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

/**
 * @return array<string, string>
 */
function validRegistration(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ], $overrides);
}

it('registers a user and authenticates them', function () {
    $this->post('/register', validRegistration())->assertRedirect();

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Jane Doe')
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->password)->not->toBe('password123');

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid registrations', function (array $overrides) {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->post('/register', validRegistration($overrides))->assertSessionHasErrors();

    expect(User::count())->toBe(1);
    $this->assertGuest();
})->with([
    'missing name' => [['name' => '']],
    'missing email' => [['email' => '']],
    'missing password' => [['password' => '', 'password_confirmation' => '']],
    'invalid email' => [['email' => 'not-an-email']],
    'duplicate email' => [['email' => 'taken@example.com']],
    'mismatched confirmation' => [['password_confirmation' => 'different123']],
]);

it('logs in with valid credentials', function () {
    $user = User::factory()->create(['password' => 'password123']);

    $this->post('/login', ['email' => $user->email, 'password' => 'password123']);

    $this->assertAuthenticatedAs($user);
});

it('does not log in with invalid credentials', function () {
    $user = User::factory()->create(['password' => 'password123']);

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);

    $this->assertGuest();
});

it('logs out an authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});

it('registers and authenticates a user from the native register screen', function () {
    $screen = new RegisterScreen;
    $screen->name = 'Jane Doe';
    $screen->email = 'jane@example.com';
    $screen->password = 'password123';
    $screen->password_confirmation = 'password123';

    try {
        $screen->register();
    } catch (Throwable) {
        // back() requires a native runtime; the user is created before it.
    }

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});

it('dispatches the Registered event from the native register screen', function () {
    Event::fake([Registered::class]);

    $screen = new RegisterScreen;
    $screen->name = 'Jane Doe';
    $screen->email = 'jane@example.com';
    $screen->password = 'password123';
    $screen->password_confirmation = 'password123';

    try {
        $screen->register();
    } catch (Throwable) {
        // back() requires a native runtime; the event is dispatched before it.
    }

    Event::assertDispatched(Registered::class, fn (Registered $event) => $event->user->email === 'jane@example.com');
});

it('masks password fields on the native auth screens', function () {
    expect(file_get_contents(resource_path('views/native/login.blade.php')))
        ->toContain('placeholder="Password" :secure="true"');

    $register = file_get_contents(resource_path('views/native/register.blade.php'));

    expect($register)
        ->toContain('placeholder="Password" :secure="true"')
        ->toContain('placeholder="Confirm password" :secure="true"');
});

it('logs out from the native notes screen', function () {
    $this->actingAs(User::factory()->create());

    (new NotesIndexScreen)->logout();

    $this->assertGuest();
});

it('requires valid details on the native register screen', function () {
    (new RegisterScreen)->register();
})->throws(ValidationException::class);

it('fails native login validation without credentials', function () {
    (new LoginScreen)->login();
})->throws(ValidationException::class);

it('fails native login with wrong credentials', function () {
    $user = User::factory()->create(['password' => 'password123']);

    $screen = new LoginScreen;
    $screen->email = $user->email;
    $screen->password = 'wrong-password';

    $screen->login();
})->throws(ValidationException::class);

it('logs in from the native login screen', function () {
    $user = User::factory()->create(['password' => 'password123']);

    $screen = new LoginScreen;
    $screen->email = $user->email;
    $screen->password = 'password123';

    try {
        $screen->login();
    } catch (Throwable) {
        // back() requires a native runtime; the login happens before it.
    }

    $this->assertAuthenticatedAs($user);
});
