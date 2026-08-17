<x-layouts.app title="New note">
    <form class="field" action="{{ route('notes.store') }}" method="POST">
        @csrf
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>

        <label for="body">Body</label>
        <textarea id="body" name="body" rows="6" required>{{ old('body') }}</textarea>

        <button type="submit">Save note</button>
    </form>

    <p><a href="{{ route('notes.index') }}">&larr; Back to notes</a></p>
</x-layouts.app>
