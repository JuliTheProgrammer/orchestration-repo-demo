<?php

namespace App\Native;

use App\Models\Post;
use Illuminate\View\View;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Edge\NativeComponent;

class PostsIndexScreen extends NativeComponent
{
    /** @var array<int, array{id: int, title: string, body: string, author: string}> */
    public array $posts = [];

    public function mount(): void
    {
        $this->loadPosts();
    }

    public function onResume(): void
    {
        $this->loadPosts();
    }

    public function loadPosts(): void
    {
        $this->posts = Post::with('user')
            ->latest()
            ->get()
            ->map(fn (Post $post): array => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'author' => $post->user->name,
            ])
            ->all();
    }

    public function navTitle(): string
    {
        return 'Posts';
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()
            ->action(NavAction::make('add')->icon('add')->a11yLabel('New post')->press('createPost'));
    }

    public function createPost(): void
    {
        $this->navigate('/app/posts/create');
    }

    public function render(): View
    {
        return view('native.posts-index');
    }
}
