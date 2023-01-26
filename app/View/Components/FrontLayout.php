<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FrontLayout extends Component
{
    public $title;

    public function __construct($title = null)
    {
        $this->title = $title ?? config('app.name');
    }

    public function render()
    {
        return view('layouts.front');
    }
}
