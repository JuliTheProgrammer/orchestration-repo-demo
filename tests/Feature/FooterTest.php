<?php

dataset('native views', [
    'notes-index' => ['notes-index', ['notes' => [], 'lastReactedNoteId' => null, 'lastReactedEmoji' => null]],
    'note-form' => ['note-form', ['noteId' => null, 'title' => '', 'body' => '']],
    'feedback' => ['feedback', ['message' => '']],
    'login' => ['login', ['email' => '', 'password' => '']],
    'register' => ['register', ['name' => '', 'email' => '', 'password' => '', 'password_confirmation' => '']],
    'posts-index' => ['posts-index', ['posts' => []]],
    'post-form' => ['post-form', ['postId' => null, 'title' => '', 'body' => '']],
]);

it('renders the copyright footer on the native view', function (string $view, array $data) {
    $html = view('native.'.$view, $data)->render();

    expect($html)->toContain('© '.now()->year.' '.config('app.name'));
})->with('native views');

it('includes the footer partial exactly once in the native view', function (string $view, array $data) {
    $contents = file_get_contents(resource_path('views/native/'.$view.'.blade.php'));

    expect(substr_count($contents, "@include('native.partials.footer')"))->toBe(1);
})->with('native views');
