<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        <outlined-text-input value="{{ $title }}" placeholder="Title" @change="updateTitle" />
        <outlined-text-input value="{{ $body }}" placeholder="Write your note..." :multiline="true" @change="updateBody" />

        @if ($noteId)
            <button label="Delete" @press="delete" color="#DC2626" labelColor="#FFFFFF" :fontSize="14" />
        @endif
    </column>
</column>
