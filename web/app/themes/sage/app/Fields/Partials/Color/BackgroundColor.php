<?php

namespace App\Fields\Partials\Color;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class BackgroundColor extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('backgroundColor');

        $fields
            ->addSelect('bg', [
                'label' => 'Background Color',
                'required' => 0,
                'wrapper' => [
                    'width' => '25%',
                ],
                'choices' => [
                    ['black' => 'Black'],
                    ['white' => 'White'],
                    ['primary' => 'Primary'],
                    ['secondary' => 'Secondary'],
                    ['[#f4f3ef]' => '#f4f3ef']
                ],
                'default_value' => 'primary',
            ]);

        return $fields;
    }
}
