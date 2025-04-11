<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BrandCard extends Component
{
    public $brand;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $termId,
    ) {
        $this->brand = get_term($termId, 'pa_brand');
        $this->brand->permalink = get_term_link($this->brand->term_id, $this->brand->taxonomy);
        $this->brand->img = wp_get_attachment_image_src(ht_get_field('brand_img', $this->brand->taxonomy.'_'.$this->brand->term_id), 'full')[0] ?? wc_placeholder_img_src('full');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.brand-card');
    }
}
