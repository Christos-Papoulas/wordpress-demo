<?php

namespace App\Fields\Partials\Color;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class BorderColor extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('borderColor');

        $fields
            ->addSelect('border_color', [
                'label' => 'Border Color',
                'required' => 0,
                'wrapper' => [
                    'width' => '25%',
                ],
                'choices' => [
                    ['body' => 'Body'],
                    ['black' => 'Black'],
                    ['white' => 'White'],
                    ['primary' => 'Primary'],
                    ['secondary' => 'Secondary'],
                ],
                'default_value' => 'primary',
            ]);

        return $fields;
    }
}
