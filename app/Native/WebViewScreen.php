<?php

namespace App\Native;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class WebViewScreen extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Notes';
    }

    public function render(): View
    {
        return view('native.webview-screen');
    }
}
