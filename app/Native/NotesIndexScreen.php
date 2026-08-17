<?php

namespace App\Native;

use App\Models\Note;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class NotesIndexScreen extends NativeComponent
{
    /** @var array<int, array{id: int, title: string, body: string}> */
    public array $notes = [];

    public function mount(): void
    {
        $this->loadNotes();
    }

    public function onResume(): void
    {
        $this->loadNotes();
    }

    public function loadNotes(): void
    {
        $this->notes = Note::latest()->get(['id', 'title', 'body'])->toArray();
    }

    public function navTitle(): string
    {
        return 'Notes';
    }

    public function render(): View
    {
        return view('native.notes-index');
    }
}
