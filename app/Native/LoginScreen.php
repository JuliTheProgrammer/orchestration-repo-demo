<?php

namespace App\Native;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Fortify\Fortify;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class LoginScreen extends NativeComponent
{
    public string $email = '';

    public string $password = '';

    public function navTitle(): string
    {
        return 'Log in';
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->action(NavAction::make('login')->icon('check')->a11yLabel('Log in')->press('login'));
    }

    public function updateEmail(string $value): void
    {
        $this->email = $value;
    }

    public function updatePassword(string $value): void
    {
        $this->password = $value;
    }

    public function openRegister(): void
    {
        $this->navigate('/app/register');
    }

    public function login(): void
    {
        $username = Fortify::username();

        $credentials = validator(
            [$username => $this->email, 'password' => $this->password],
            [$username => ['required', 'string'], 'password' => ['required', 'string']],
        )->validate();

        if (! app(StatefulGuard::class)->attempt($credentials)) {
            throw ValidationException::withMessages([
                $username => [trans('auth.failed')],
            ]);
        }

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        $this->back();
    }

    public function render(): View
    {
        return view('native.login');
    }
}
