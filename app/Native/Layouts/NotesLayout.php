<?php

namespace App\Native\Layouts;

use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

class NotesLayout extends NativeLayout
{
    public function usesNativeChrome(): bool
    {
        return true;
    }

    public function navBar(NativeComponent $screen): ?NavBar
    {
        $showBack = ! method_exists($screen, 'showsNavBack')
            || $screen->showsNavBack();

        return NavBar::make()
            ->title($screen->navTitle())
            ->back($showBack);
    }
}
