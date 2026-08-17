<x-layouts.app title="Edit note">
    <form class="field" action="{{ route('notes.update', $note) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $note->title) }}" required>

        <label for="body">Body</label>
        <textarea id="body" name="body" rows="6" required>{{ old('body', $note->body) }}</textarea>

        <button type="submit">Update note</button>
    </form>

    <p><a href="{{ route('notes.index') }}">&larr; Back to notes</a></p>
</x-layouts.app>
