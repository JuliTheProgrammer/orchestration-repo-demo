<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        <outlined-text-input value="{{ $message }}" placeholder="Tell us what you think..." :multiline="true" @change="updateMessage" />
    </column>
</column>
