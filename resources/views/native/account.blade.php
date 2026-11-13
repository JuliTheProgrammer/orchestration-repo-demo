<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        @if ($isRegistering)
            <outlined-text-input value="{{ $name }}" placeholder="Name" @change="updateName" />
        @endif
        <outlined-text-input value="{{ $email }}" placeholder="Email" @change="updateEmail" />
        <outlined-text-input value="{{ $password }}" placeholder="Password" :secure="true" @change="updatePassword" />

        <button label="{{ $isRegistering ? 'Create account' : 'Log in' }}" @press="submit" />
        <text @press="toggleMode" class="text-[14] text-theme-on-surface-variant">{{ $isRegistering ? 'Already have an account? Log in' : 'No account yet? Create one' }}</text>
    </column>
</column>
