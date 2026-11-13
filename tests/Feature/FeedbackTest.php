<?php

use App\Native\FeedbackScreen;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

it('emails submitted feedback to the developers', function () {
    config(['services.feedback.to' => 'dev@example.com']);
    Event::fake([MessageSent::class]);

    $screen = new FeedbackScreen;
    $screen->message = 'Love the app!';

    try {
        $screen->send();
    } catch (Throwable) {
        // back() requires a native runtime; the mail is sent before it.
    }

    Event::assertDispatched(MessageSent::class, fn (MessageSent $event) => $event->message->getTo()[0]->getAddress() === 'dev@example.com'
        && str_contains($event->message->getTextBody(), 'Love the app!'));
});

it('requires a feedback message', function () {
    (new FeedbackScreen)->send();
})->throws(ValidationException::class);
