<?php

use App\Http\Controllers\NoteController;
use App\Native\WebViewScreen;
use Illuminate\Support\Facades\Route;

Route::native('/home', WebViewScreen::class);

Route::redirect('/', '/notes');

Route::resource('notes', NoteController::class);
