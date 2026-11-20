<?php

namespace App\Native;

use App\Models\Post;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\View\View;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class PostFormScreen extends NativeComponent
{
    public ?int $postId = null;

    public string $title = '';

    public string $body = '';

    public function mount(): void
    {
        $id = $this->param('id');

        if ($id !== null) {
            $post = Post::findOrFail($id);

            $this->postId = $post->id;
            $this->title = $post->title;
            $this->body = $post->body;
        }
    }

    public function navTitle(): string
    {
        return $this->postId ? 'Edit post' : 'New post';
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->action(NavAction::make('save')->icon('check')->a11yLabel('Save')->press('save'));
    }

    public function updateTitle(string $value): void
    {
        $this->title = $value;
    }

    public function updateBody(string $value): void
    {
        $this->body = $value;
    }

    public function save(): void
    {
        $user = app(StatefulGuard::class)->user();

        if ($user === null) {
            $this->navigate('/app/login');

            return;
        }

        $validated = validator([
            'title' => $this->title,
            'body' => $this->body,
        ], [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ])->validate();

        if ($this->postId) {
            $user->posts()->findOrFail($this->postId)->update($validated);
        } else {
            $user->posts()->create($validated);
        }

        $this->back();
    }

    public function delete(): void
    {
        $user = app(StatefulGuard::class)->user();

        if ($user === null) {
            $this->navigate('/app/login');

            return;
        }

        if ($this->postId) {
            $user->posts()->findOrFail($this->postId)->delete();
        }

        $this->back();
    }

    public function render(): View
    {
        return view('native.post-form');
    }
}
