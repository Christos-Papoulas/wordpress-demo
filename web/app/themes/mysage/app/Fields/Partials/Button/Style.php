<?php

namespace App\Fields\Partials\Button;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Style extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('buttonStyle');

        $fields
            ->addSelect('size', [
                'label' => 'Size',
                'required' => 0,
                'wrapper' => [
                    'width' => '25%',
                ],
                'choices' => [
                    ['btn-md' => 'Medium'],
                    ['btn-lg' => 'Large'],
                    ['btn-sm' => 'Small'],
                ],
                'default_value' => 'btn-md',
                'wpml_cf_preferences' => 1, // copy
            ])
            ->addSelect('style', [
                'label' => 'Style',
                'required' => 0,
                'wrapper' => [
                    'width' => '25%',
                ],
                'choices' => [
                    ['btn-solid-primary' => 'Solid Primary'],
                    ['btn-solid-secondary' => 'Solid Secondary'],
                    ['btn-solid-white' => 'Solid White'],
                    ['btn-solid-secondary' => 'Outlined Primary'],
                    ['btn-outlined-transparent' => 'Outlined Transparent'],
                    ['btn-solid-danger' => 'Solid Danger'],
                ],
                'default_value' => 'btn-solid-primary',
                'wpml_cf_preferences' => 1, // copy
            ]);

        return $fields;
    }
}
