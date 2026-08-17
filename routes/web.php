<?php

use App\Native\NoteFormScreen;
use App\Native\NotesIndexScreen;
use Illuminate\Support\Facades\Route;

Route::native('/app/notes', NotesIndexScreen::class);
Route::native('/app/notes/create', NoteFormScreen::class);
Route::native('/app/notes/{id}', NoteFormScreen::class);
