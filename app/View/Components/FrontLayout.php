<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;

class FrontLayout extends Component
{
    public $title;
    public $categories;

    public function __construct($title = null)
    {
        $this->title = $title ?? config('app.name');
        $this->categories = Category::whereNull('parent_id')->with('children')->get();

    }

    public function render()
    {
        return view('layouts.front');
    }
}
