<?php

use App\Native\FeedbackScreen;
use App\Native\Layouts\NotesLayout;
use App\Native\NoteFormScreen;
use App\Native\NotesIndexScreen;
use Illuminate\Support\Facades\Route;

Route::nativeGroup(NotesLayout::class, function () {
    Route::native('/app/notes', NotesIndexScreen::class);
    Route::native('/app/feedback', FeedbackScreen::class);
    Route::native('/app/notes/create', NoteFormScreen::class);
    Route::native('/app/notes/{id}', NoteFormScreen::class);
});
