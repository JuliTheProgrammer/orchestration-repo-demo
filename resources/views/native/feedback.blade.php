<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        <column class="w-full rounded-2xl bg-theme-surface-variant p-4 gap-4">
            <text class="text-[13] font-semibold text-theme-on-surface-variant">Feedback</text>
            <outlined-text-input value="{{ $message }}" placeholder="Tell us what you think..." :multiline="true" @change="updateMessage" />
        </column>
    </column>
</column>
