<?php

use App\Models\Post;
use App\Models\User;
use App\Native\NotesIndexScreen;
use App\Native\PostFormScreen;
use App\Native\PostsIndexScreen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('creates a post with a user from the factory', function () {
    $post = Post::factory()->create();

    expect($post->exists)->toBeTrue()
        ->and($post->user_id)->not->toBeNull()
        ->and($post->title)->toBeString()->not->toBeEmpty()
        ->and($post->body)->toBeString()->not->toBeEmpty();
});

it('relates posts and users', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    expect($post->user->is($user))->toBeTrue()
        ->and($user->posts)->toHaveCount(1)
        ->and($user->posts->first()->is($post))->toBeTrue();
});

it('deletes posts when their user is deleted', function () {
    $post = Post::factory()->create();

    $post->user->delete();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('loads posts newest first with their author on the posts index screen', function () {
    $alice = User::factory()->create(['name' => 'Alice']);
    $bob = User::factory()->create(['name' => 'Bob']);
    $older = Post::factory()->for($alice)->create(['title' => 'Older', 'created_at' => now()->subDay()]);
    $newer = Post::factory()->for($bob)->create(['title' => 'Newer', 'created_at' => now()]);

    $screen = new PostsIndexScreen;
    $screen->loadPosts();

    expect($screen->posts)->toHaveCount(2)
        ->and($screen->posts[0])->toBe([
            'id' => $newer->id,
            'title' => 'Newer',
            'body' => $newer->body,
            'author' => 'Bob',
        ])
        ->and($screen->posts[1]['id'])->toBe($older->id)
        ->and($screen->posts[1]['author'])->toBe('Alice');
});

it('shows an empty state on the posts index view', function () {
    $html = view('native.posts-index', ['posts' => []])->render();

    expect($html)->toContain('No posts yet.');
});

it('creates a post for the acting user from the post form screen', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $screen = new PostFormScreen;
    $screen->title = 'Hello';
    $screen->body = 'World';

    try {
        $screen->save();
    } catch (Throwable) {
        // back() requires a native runtime; the post is created before it.
    }

    $this->assertDatabaseHas('posts', ['user_id' => $user->id, 'title' => 'Hello', 'body' => 'World']);
});

it('validates the post form', function (string $title, string $body) {
    $this->actingAs(User::factory()->create());

    $screen = new PostFormScreen;
    $screen->title = $title;
    $screen->body = $body;

    $screen->save();
})->with([
    'empty title' => ['', 'Body'],
    'title too long' => [str_repeat('a', 256), 'Body'],
    'empty body' => ['Title', ''],
])->throws(ValidationException::class);

it('does not create a post for a guest', function () {
    $screen = new PostFormScreen;
    $screen->title = 'Hello';
    $screen->body = 'World';

    try {
        $screen->save();
    } catch (Throwable) {
        // navigate() requires a native runtime; nothing is saved before it.
    }

    expect(Post::count())->toBe(0);
});

it('updates an existing own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $this->actingAs($user);

    $screen = new PostFormScreen;
    $screen->postId = $post->id;
    $screen->title = 'Updated';
    $screen->body = 'Updated body';

    try {
        $screen->save();
    } catch (Throwable) {
        // back() requires a native runtime; the post is updated before it.
    }

    expect($post->fresh()->title)->toBe('Updated')
        ->and($post->fresh()->body)->toBe('Updated body');
});

it('does not update another user\'s post', function () {
    $post = Post::factory()->create(['title' => 'Original']);
    $this->actingAs(User::factory()->create());

    $screen = new PostFormScreen;
    $screen->postId = $post->id;
    $screen->title = 'Hijacked';
    $screen->body = 'Hijacked body';

    try {
        $screen->save();
    } catch (Throwable) {
        // The post is not found for the acting user.
    }

    expect($post->fresh()->title)->toBe('Original');
});

it('does not delete another user\'s post', function () {
    $post = Post::factory()->create();
    $this->actingAs(User::factory()->create());

    $screen = new PostFormScreen;
    $screen->postId = $post->id;

    try {
        $screen->delete();
    } catch (Throwable) {
        // The post is not found for the acting user.
    }

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});

it('deletes an own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $this->actingAs($user);

    $screen = new PostFormScreen;
    $screen->postId = $post->id;

    try {
        $screen->delete();
    } catch (Throwable) {
        // back() requires a native runtime; the post is deleted before it.
    }

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('links to posts from the notes index screen', function () {
    expect(method_exists(NotesIndexScreen::class, 'openPosts'))->toBeTrue()
        ->and(file_get_contents(app_path('Native/NotesIndexScreen.php')))->toContain("->press('openPosts')");
});
