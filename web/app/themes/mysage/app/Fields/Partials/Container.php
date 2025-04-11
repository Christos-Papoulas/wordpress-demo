<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Container extends Partial
{
    /**
     * The partial field group.
     *
     * @return array
     */
    public function fields()
    {
        $container = Builder::make('container');

        $container
            ->addSelect('container', [
                'label' => 'Container',
                'required' => 1,
                'choices' => [
                    ['' => 'Full Width'],
                    ['ht-container-no-max-width' => 'Only Paddings'],
                    ['ht-container' => 'Full HD'],
                    ['ht-container-large' => 'Large'],
                    ['ht-container-medium' => 'Medium'],
                    ['ht-container-small' => 'Small'],
                ],
                'default_value' => 'ht-container-no-max-width',
                'wpml_cf_preferences' => 1, // copy
            ]);

        return $container;
    }
}
