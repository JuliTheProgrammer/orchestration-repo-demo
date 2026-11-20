<?php

use App\Models\Note;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('logs created, updated and deleted events for models', function () {
    $note = Note::create(['title' => 'A', 'body' => 'B']);
    $note->update(['title' => 'C']);
    $note->delete();

    expect(Activity::pluck('event')->all())->toBe(['created', 'updated', 'deleted']);
});

it('attributes model changes to the authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = $user->posts()->create(['title' => 'Hi', 'body' => 'There']);

    $activity = Activity::where('subject_type', Post::class)->where('subject_id', $post->id)->first();

    expect($activity->causer->is($user))->toBeTrue();
});

it('does not log an update that changes nothing', function () {
    $note = Note::factory()->create();
    Activity::query()->delete();

    $note->update(['title' => $note->title]);

    expect(Activity::count())->toBe(0);
});

it('logs login, logout and failed login events', function () {
    $user = User::factory()->create();

    event(new Login('web', $user, false));
    event(new Logout('web', $user));
    event(new Failed('web', null, ['email' => 'x@example.com']));

    expect(Activity::where('log_name', 'auth')->pluck('description')->all())
        ->toBe(['logged in', 'logged out', 'failed login attempt']);
});
