<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class ProductAttributeColor extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        
        $productAttributeColor = Builder::make('productAttributeColor');

        if(!function_exists('wc_get_attribute_taxonomies')){
            return $productAttributeColor->build();
        }   
        // Get all registered product attributes
        $product_attributes = wc_get_attribute_taxonomies();

        $valid_taxonomies = [];
        foreach ($product_attributes as $att) {
            $display_type = get_option( "wc_attribute_display_type-$att->attribute_id" );
            if($display_type == 'color'){
                $valid_taxonomies[] = 'pa_' . $att->attribute_name;
            }
        }
            
        // dd($valid_taxonomies);

        if(!empty($valid_taxonomies)){

            $location = null;

            foreach ($valid_taxonomies as $key => $valid_taxonomy) {
                if($key == 0){
                    $location = $productAttributeColor->setLocation('taxonomy', '==', $valid_taxonomy);
                }else{
                    $location->or('taxonomy', '==', $valid_taxonomy);
                }
            }

            $productAttributeColor
                    ->addSelect('display_color_style', [
                        'label' => 'Style',
                        'choices' => ['hex','img'],
                        'wpml_cf_preferences' => 1 // copy
                    ])
                    ->addColorPicker('hexcolor', [
                        'conditional_logic' => [
                            'field' => 'display_color_style', 'operator' => '==', 'value' => 'hex'
                        ],
                        'label' => 'Hex Color',
                        'instructions' => '',
                        'required' => 0,
                        'enable_opacity' => 0,
                        'return_format' => 'string',
                        'default_value' => '',
                        'wpml_cf_preferences' => 1 // copy
                    ])
                    ->addImage('attr_img', [
                        'conditional_logic' => [
                            'field' => 'display_color_style', 'operator' => '==', 'value' => 'img'
                        ],
                        'label' => 'Image',
                        'required' => 0,
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'max_width' => '64',
                        'max_height' => '64',
                        'wpml_cf_preferences' => 1 // copy
                    ]);

        }

        return $productAttributeColor->build();
    }
}
