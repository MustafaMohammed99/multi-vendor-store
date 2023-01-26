<?php

namespace App\View\Components;

use App\Models\Wishlist;
use Illuminate\View\Component;

class WishlistMenu extends Component
{
    public $wishlists;
    public $s;

    public function __construct()
    {
        // $this->wishlists = Wishlist::wiht('product')->get();
           $this->wishlists = Wishlist::with('product')->get();
        $this->s = $this->wishlists->count();
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
