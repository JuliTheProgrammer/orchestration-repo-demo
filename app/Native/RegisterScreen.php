<?php

namespace App\Native;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\View\View;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class RegisterScreen extends NativeComponent
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function navTitle(): string
    {
        return 'Create account';
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->action(NavAction::make('register')->icon('check')->a11yLabel('Register')->press('register'));
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

    public function updatePasswordConfirmation(string $value): void
    {
        $this->password_confirmation = $value;
    }

    public function register(): void
    {
        $user = app(CreateNewUser::class)->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        event(new Registered($user));

        app(StatefulGuard::class)->login($user);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        $this->back();
    }

    public function render(): View
    {
        return view('native.register');
    }
}
