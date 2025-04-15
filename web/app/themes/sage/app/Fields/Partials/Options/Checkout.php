<?php

namespace App\Fields\Partials\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Checkout extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('checkout');

        $fields
            ->addGroup('invoice', ['label' => 'Invoice'])
            ->addRadio('validation_type', [
                'label' => 'Validation Type',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '25%',
                ],
                'choices' => [
                    ['vies' => 'VIES'],
                    ['aade' => 'ΑΑΔΕ'],
                ],
                'allow_null' => 0,
                'other_choice' => 0,
                'save_other_choice' => 0,
                'default_value' => 'vies',
                'layout' => 'vertical',
                'return_format' => 'value',
            ])
            ->addText('vat_id', [
                'label' => 'VAT ID',
                'wrapper' => ['width' => '25%'],
                'conditional_logic' => [
                    'field' => 'validation_type', 'operator' => '==', 'value' => 'aade',
                ],
            ])
            ->addText('iapr_username', [
                'label' => 'IAPR Username',
                'wrapper' => ['width' => '25%'],
                'conditional_logic' => [
                    'field' => 'validation_type', 'operator' => '==', 'value' => 'aade',
                ],
            ])
            ->addText('iapr_password', [
                'label' => 'IAPR Password',
                'wrapper' => ['width' => '25%'],
                'conditional_logic' => [
                    'field' => 'validation_type', 'operator' => '==', 'value' => 'aade',
                ],
            ])
            ->endGroup();

        return $fields;
    }
}
