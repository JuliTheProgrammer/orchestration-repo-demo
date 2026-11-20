<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        <outlined-text-input value="{{ $name }}" placeholder="Name" @change="updateName" />
        <outlined-text-input value="{{ $email }}" placeholder="Email" @change="updateEmail" />
        <outlined-text-input value="{{ $password }}" placeholder="Password" :secure="true" @change="updatePassword" />
        <outlined-text-input value="{{ $password_confirmation }}" placeholder="Confirm password" :secure="true" @change="updatePasswordConfirmation" />
        <button label="Create account" @press="register" :fontSize="14" />
    </column>
    @include('native.partials.footer')
</column>
