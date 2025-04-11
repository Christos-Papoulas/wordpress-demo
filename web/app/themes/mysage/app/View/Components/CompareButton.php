<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CompareButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $product,
        public $myCompareList,
        public $class = 'w-auto'
    ) {
        $this->product = $product;
        $this->myCompareList = $myCompareList ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.compare-button');
    }
}
