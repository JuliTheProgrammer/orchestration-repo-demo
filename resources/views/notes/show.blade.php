<x-layouts.app :title="$note->title">
    <p><a href="{{ route('notes.index') }}">&larr; Back to notes</a></p>

    <div class="note">
        <h2>{{ $note->title }}</h2>
        <p>{{ $note->body }}</p>
        <div class="actions">
            <a href="{{ route('notes.edit', $note) }}">Edit</a>
            <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?')">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.app>
