<x-layouts.app title="Notes">
    <p><a href="{{ route('notes.create') }}">+ New note</a></p>

    @forelse ($notes as $note)
        <div class="note">
            <h2><a href="{{ route('notes.show', $note) }}">{{ $note->title }}</a></h2>
            <p>{{ Str::limit($note->body, 120) }}</p>
            <div class="actions">
                <a href="{{ route('notes.edit', $note) }}">Edit</a>
                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p>No notes yet.</p>
    @endforelse
</x-layouts.app>
