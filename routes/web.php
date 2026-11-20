<?php

use App\Native\FeedbackScreen;
use App\Native\Layouts\NotesLayout;
use App\Native\LoginScreen;
use App\Native\NoteFormScreen;
use App\Native\NotesIndexScreen;
use App\Native\PostFormScreen;
use App\Native\PostsIndexScreen;
use App\Native\RegisterScreen;
use Illuminate\Support\Facades\Route;

Route::nativeGroup(NotesLayout::class, function () {
    Route::native('/app/notes', NotesIndexScreen::class);
    Route::native('/app/feedback', FeedbackScreen::class);
    Route::native('/app/login', LoginScreen::class);
    Route::native('/app/register', RegisterScreen::class);
    Route::native('/app/notes/create', NoteFormScreen::class);
    Route::native('/app/notes/{id}', NoteFormScreen::class);
    Route::native('/app/posts', PostsIndexScreen::class);
    Route::native('/app/posts/create', PostFormScreen::class);
    Route::native('/app/posts/{id}', PostFormScreen::class);
});
