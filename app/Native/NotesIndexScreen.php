<?php

namespace App\Native;

use App\Models\Note;
use Illuminate\View\View;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class NotesIndexScreen extends NativeComponent
{
    /** @var array<int, array{id: int, title: string, body: string}> */
    private array $allNotes = [];

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
        $this->allNotes = Note::latest()->get(['id', 'title', 'body'])->toArray();
        $this->notes = $this->allNotes;
    }

    public function navTitle(): string
    {
        return 'Notes';
    }

    public function showsNavBack(): bool
    {
        return false;
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->searchBar('Search notes...', 'searchNotes')
            ->action(NavAction::make('add')->icon('add')->a11yLabel('New note')->press('createNote'));
    }

    public function createNote(): void
    {
        $this->navigate('/app/notes/create');
    }

    public function searchNotes(string $query): void
    {
        $needle = trim($query);

        if ($needle === '') {
            $this->notes = $this->allNotes;

            return;
        }

        $lower = str($needle)->lower();

        $this->notes = collect($this->allNotes)
            ->filter(fn ($note) => str($note['title'])->lower()->contains($lower)
                || str($note['body'])->lower()->contains($lower))
            ->values()
            ->toArray();
    }

    public function render(): View
    {
        return view('native.notes-index');
    }
}
