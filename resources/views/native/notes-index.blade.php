<column class="w-full h-full bg-theme-background safe-area">
    <row class="w-full px-4 py-3 items-center justify-between">
        <text class="text-[20] font-bold text-theme-on-surface">Notes</text>
        <column @press="navigate('/app/notes/create')" class="w-[32] h-[32] items-center justify-center">
            <icon name="add" :size="24" />
        </column>
    </row>

    <divider class="w-full" />

    <scroll-view class="w-full h-full">
        <column class="w-full gap-0">
            @forelse ($notes as $note)
                <column @press="navigate('/app/notes/{{ $note['id'] }}')" class="w-full px-4 py-3 gap-1">
                    <text class="text-[16] font-bold text-theme-on-surface">{{ $note['title'] }}</text>
                    <text class="text-[13] text-theme-on-surface-variant" :maxLines="1">{{ $note['body'] }}</text>
                </column>
                <divider class="w-full" />
            @empty
                <column class="w-full items-center py-12 gap-2">
                    <text class="text-[15] text-theme-on-surface-variant">No notes yet. Tap + to add one.</text>
                </column>
            @endforelse
        </column>
    </scroll-view>
</column>
