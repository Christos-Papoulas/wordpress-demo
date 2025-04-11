<?php

namespace App\Fields\Partials\Button;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Text extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('buttonText');

        $fields
            ->addText('text', [
                'label' => 'text',
                'required' => 0,
                'wrapper' => [
                    'width' => '25%',
                ],
                'wpml_cf_preferences' => 2, // translate
            ]);

        return $fields;
    }
}
