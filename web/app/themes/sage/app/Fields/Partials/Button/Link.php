<?php

namespace App\Fields\Partials\Button;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Link extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('buttonLink');

        $fields
            ->addLink('link', [
                'label' => 'Link',
                'required' => 0,
                'return_format' => 'array',
                'wrapper' => [
                    'width' => '25%',
                ],
                'wpml_cf_preferences' => 1, // copy
            ]);

        return $fields;
    }
}
