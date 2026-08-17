<?php

namespace App\Native;

use App\Models\Note;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class NoteFormScreen extends NativeComponent
{
    public ?int $noteId = null;

    public string $title = '';

    public string $body = '';

    public function mount(): void
    {
        $id = $this->param('id');

        if ($id !== null) {
            $note = Note::findOrFail($id);

            $this->noteId = $note->id;
            $this->title = $note->title;
            $this->body = $note->body;
        }
    }

    public function navTitle(): string
    {
        return $this->noteId ? 'Edit note' : 'New note';
    }

    public function updateTitle(string $value): void
    {
        $this->title = $value;
    }

    public function updateBody(string $value): void
    {
        $this->body = $value;
    }

    public function save(): void
    {
        $validated = validator([
            'title' => $this->title,
            'body' => $this->body,
        ], [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ])->validate();

        Note::updateOrCreate(['id' => $this->noteId], $validated);

        $this->back();
    }

    public function delete(): void
    {
        if ($this->noteId) {
            Note::destroy($this->noteId);
        }

        $this->back();
    }

    public function render(): View
    {
        return view('native.note-form');
    }
}
