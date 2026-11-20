<column class="w-full h-full bg-theme-background safe-area">
    <column class="w-full px-4 pt-4 gap-4">
        <outlined-text-input value="{{ $email }}" placeholder="Email" @change="updateEmail" />
        <outlined-text-input value="{{ $password }}" placeholder="Password" :secure="true" @change="updatePassword" />
        <button label="Log in" @press="login" :fontSize="14" />
        <button label="Create an account" @press="openRegister" :fontSize="14" />
    </column>
    @include('native.partials.footer')
</column>
