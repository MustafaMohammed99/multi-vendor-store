<?php

namespace App\View\Components;

use App\Repositories\Cart\CartRepository;
use Illuminate\View\Component;

class CartMenu extends Component
{

    public $items ;
    public $total ;


    public function __construct(CartRepository $cart)
    {
        $this->items = $cart->get();
        $this->total = $cart->total();
    }

    public function render()
    {
        return view('components.cart-menu');
    }
}
