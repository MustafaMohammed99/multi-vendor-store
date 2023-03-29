<?php

namespace App\View\Components;

use App\Models\Wishlist;
use Illuminate\View\Component;

class WishlistMenu extends Component
{
    public $wishlists;

    public function __construct()
    {
        $this->wishlists = Wishlist::with('product')->get();
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.wishlist-menu');
    }
}
