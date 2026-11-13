<?php

namespace App\Native;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class AccountScreen extends NativeComponent
{
    public bool $isRegistering = false;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function navTitle(): string
    {
        return $this->isRegistering ? 'Create account' : 'Log in';
    }

    public function updateName(string $value): void
    {
        $this->name = $value;
    }

    public function updateEmail(string $value): void
    {
        $this->email = $value;
    }

    public function updatePassword(string $value): void
    {
        $this->password = $value;
    }

    public function toggleMode(): void
    {
        $this->isRegistering = ! $this->isRegistering;
    }

    public function submit(): void
    {
        if ($this->isRegistering) {
            $this->register();
        } else {
            $this->login();
        }

        $this->navigate('/app/notes');
    }

    protected function register(): void
    {
        $validated = validator([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
        ])->validate();

        Auth::login(User::create($validated));
    }

    protected function login(): void
    {
        $credentials = validator([
            'email' => $this->email,
            'password' => $this->password,
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ])->validate();

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'Invalid email or password.']);
        }
    }

    public function render(): View
    {
        return view('native.account');
    }
}
