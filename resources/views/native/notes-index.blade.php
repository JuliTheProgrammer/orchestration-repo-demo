<column class="w-full h-full bg-theme-background safe-area">
    <scroll-view class="w-full h-full">
        <column class="w-full gap-0">
            @forelse ($notes as $note)
                <column @press="navigate('/app/notes/{{ $note['id'] }}')" class="w-full px-4 py-3 gap-1">
                    <text class="text-[16] font-bold text-theme-on-surface">{{ $note['title'] }}</text>
                    <text class="text-[13] text-theme-on-surface-variant" :maxLines="1">{{ $note['body'] }}</text>
                </column>
                <row class="px-4 pb-3 gap-4">
                    @foreach (\App\Models\Note::REACTIONS as $emoji)
                        <text @press="reactToNote({{ $note['id'] }}, '{{ $emoji }}')" class="{{ $lastReactedNoteId === $note['id'] && $lastReactedEmoji === $emoji ? 'text-[22] font-bold' : 'text-[13]' }} text-theme-on-surface-variant">{{ $emoji }} {{ $note['reactions'][$emoji] ?? 0 }}</text>
                    @endforeach
                </row>
                <divider class="w-full" />
            @empty
                <column class="w-full items-center py-12 gap-2">
                    <text class="text-[15] text-theme-on-surface-variant">No notes yet. Tap + to add one.</text>
                </column>
            @endforelse
        </column>
    </scroll-view>
</column>
