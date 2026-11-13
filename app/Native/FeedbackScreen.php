<?php

namespace App\Native;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class FeedbackScreen extends NativeComponent
{
    public string $message = '';

    public function navTitle(): string
    {
        return 'Send feedback';
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->action(NavAction::make('send')->icon('check')->a11yLabel('Send')->press('send'));
    }

    public function updateMessage(string $value): void
    {
        $this->message = $value;
    }

    public function send(): void
    {
        $validated = validator(
            ['message' => $this->message],
            ['message' => ['required', 'string', 'max:5000']],
        )->validate();

        Mail::raw($validated['message'], function (Message $mail): void {
            $mail->to(config('services.feedback.to'))->subject('App feedback');
        });

        $this->back();
    }

    public function render(): View
    {
        return view('native.feedback');
    }
}
