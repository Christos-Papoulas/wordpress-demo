<?php

namespace App\View\Components;

use Illuminate\View\Component;
use InvalidArgumentException;

class AttributeInput extends Component
{
    public string $inputName;
    public string $inputID;
    public string $attributeLabel;
    public array $transformedOptions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $displayType,
        public string $attributeName,
        public array $options,
        public null|array $allVariations,
    ) {
        $this->inputName = 'attribute_'.esc_attr(sanitize_title($attributeName));
        $this->inputID = esc_attr(sanitize_title($attributeName));
        $this->attributeLabel = wc_attribute_label( $attributeName );

        foreach($options as $option){
            $term = get_term_by('slug', $option, $attributeName);

            $this->transformedOptions[$term->slug] = [
                'termId' => $term->term_id,
                'termSlug' => $term->slug,
                'termName' => apply_filters('woocommerce_variation_option_name', $term->name, $attributeName),
            ];

            if($displayType === 'color' && $displayType !== 'size') {
                $this->transformedOptions[$term->slug]['background'] = $this->buildBackgroundForColorInput($term);
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->displayType === 'color') {
            return view('components.attribute-inputs.attribute-input-color');
        }elseif ($this->displayType === 'size') {
            return view('components.attribute-inputs.attribute-input-size');
        }elseif ($this->displayType === 'select') {
            return view('components.attribute-inputs.attribute-input-select');
        }elseif ($this->displayType === 'default') {
            return view('components.attribute-inputs.attribute-input-select');
        }
        throw new InvalidArgumentException('Display type '.$this->displayType.' not supported');
    }

    private function buildBackgroundForColorInput($term)
    {
        $background = 'radial-gradient(circle at 0 0, rgba(255, 0, 0, .7), rgba(255, 0, 0, 0) 70.71%), radial-gradient(circle at 93.3% 0, #fe0, rgba(255, 238, 0, 0) 70.71%), radial-gradient(circle at 0 80%, blue, rgba(0, 0, 255, 0) 70.71%), radial-gradient(circle at 93.3% 80%, rgba(0, 251, 255, .8), rgba(0, 251, 255, 0) 70.71%)';

        // for product images
        // foreach ($all_variations as $variation) {
        //     foreach ($variation['attributes'] as $key => $attr) {
        //         if ($input_name == $key && $value == $attr) {
        //             $background = 'url('.$variation['image']['thumb_src'].')';
        //             break 2;
        //         }
        //     }
        // }

        // for hexcolors
        if(true){
            $term_display_color_style = 'hex';
            $term_display_color_style = get_term_meta($term->term_id, 'display_color_style', true);
            
            if( $term_display_color_style == 'img'){
                $img_id = get_term_meta($term->term_id, 'attr_img', true);
                if ($img_id && !empty($img_id)) {
                    $background = 'url(' . wp_get_attachment_url($img_id) . ')';
                }
            }else{
                $hex = get_term_meta($term->term_id, 'hexcolor', true);
                if ($hex && !empty($hex)) {
                    $background = $hex;
                }
            }
        }

        return $background;
    }
}
