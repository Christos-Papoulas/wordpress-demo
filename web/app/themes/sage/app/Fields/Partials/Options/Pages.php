<?php

namespace App\Fields\Partials\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Pages extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('pages');

        $fields
            ->addPostObject('contact_page', [
                'label' => 'Contant Page',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
                'post_type' => ['page'],
                'taxonomy' => [],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ])
            ->addPostObject('wishlist_page', [
                'label' => 'Wishlist Page',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
                'post_type' => ['page'],
                'taxonomy' => [],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ]);

        return $fields;
    }
}
