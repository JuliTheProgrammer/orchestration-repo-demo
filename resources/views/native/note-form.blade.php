<column class="w-full h-full bg-theme-background safe-area">
    <row class="w-full px-4 py-3 items-center justify-between">
        <column @press="back" class="w-[32] h-[32] items-center justify-center">
            <icon name="arrow_back" :size="22" />
        </column>
        <text class="text-[17] font-bold text-theme-on-surface">{{ $noteId ? 'Edit note' : 'New note' }}</text>
        <button label="Save" @press="save" color="#2563EB" labelColor="#FFFFFF" :fontSize="14" />
    </row>

    <divider class="w-full" />

    <column class="w-full px-4 pt-4 gap-4">
        <outlined-text-input value="{{ $title }}" placeholder="Title" @change="updateTitle" />
        <outlined-text-input value="{{ $body }}" placeholder="Write your note..." :multiline="true" @change="updateBody" />

        @if ($noteId)
            <button label="Delete" @press="delete" color="#DC2626" labelColor="#FFFFFF" :fontSize="14" />
        @endif
    </column>
</column>
