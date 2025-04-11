<?php

namespace App\View\Components;

use App\HT\Services\Product\ProductService;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $productCardData;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $product,
        public null|string $listID = null,
        public null|string $listName = null,
    ) {
        $this->productCardData = ProductService::createProductCardData($product);
        $this->productCardData['list_id'] = $listID;
        $this->productCardData['list_name'] = $listName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product-card');
    }
}
