<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class Product extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        
        $product =  Builder::make('product_custom_fields');

        $product
            ->setLocation('post_type', '==', 'product');

        $product
            ->addUrl('video', [
                'label' => 'Video URL',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
            ]);

        return $product->build();
    }
}
