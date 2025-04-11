<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\HT\Services\Wishlist;

class WishlistButton extends Component
{
    public bool $inWishlist = false;
    public $wishlist;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public \WC_Product $product,
        public string $context = 'product-card',
        public string $class = 'w-auto',
    ){
        $this->wishlist = app(Wishlist::class);
        $this->product = $product;
        $this->inWishlist = $this->wishlist->checkIfProductIsInWihslist($this->product->get_id());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.wishlist-button');
    }
}
