<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class Store extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $storeFields = Builder::make('store');

        $storeFields
            ->setLocation('post_type', '==', 'store');

        $storeFields
            ->addGroup('store_custom_fields', ['label' => 'Custom Fields', 'layout' => 'block'])

                ->addSelect('local_pickup_enabled', [
                    'label' => 'Παραλαβή από το κατάστημα',
                    'instructions' => 'Επιτρέπεται η παραλαβή από το κατάστημα;',
                    'choices' => [
                        ['yes' => 'Ναι'],
                        ['no' => 'Όχι']
                    ],
                    'default_value' => 'no',
                    'wrapper' => ['width' => '20%'],
                    'wpml_cf_preferences' => 1 // copy
                ])

                ->addText('address', [
                    'label' => 'Οδός', 
                    'wrapper' => ['width' => '40%'], 
                    'wpml_cf_preferences' => 2 // translate
                ])
                ->addText('address_number', [
                    'label' => 'Αριθμός', 
                    'wrapper' => ['width' => '20%'],
                    'wpml_cf_preferences' => 1 // copy
                ])
                ->addText('zip_code', [
                    'label' => 'Τ.Κ.', 
                    'wrapper' => ['width' => '30%'],
                    'wpml_cf_preferences' => 1 // copy
                ])
                ->addRepeater('phones', [
                    'label' => 'Τηλέφωνα', 
                    'wrapper' => ['width' => '50%'],
                    'button_label' => 'Προσθήκη Τηλεφώνου',
                    'wpml_cf_preferences' => 1 // copy
                ])
                    ->addText('number', [
                        'label' => 'Αριθμός',
                        'wpml_cf_preferences' => 1 // copy
                    ])
                    ->addSelect('type', [
                        'label' => 'Τύπος',
                        'choices' => [
                            'landline' => 'Σταθερό',
                            'mobile' => 'Κινητό'
                        ],
                        'wpml_cf_preferences' => 1 // copy
                    ])
                ->endRepeater()
                ->addRepeater('emails', [
                    'button_label' => 'Προσθήκη Email', 
                    'wrapper' => ['width' => '50%'],
                    'wpml_cf_preferences' => 1 // copy
                ])
                    ->addEmail('email', [
                        'label' => 'Email',
                        'wpml_cf_preferences' => 1 // copy
                    ])
                ->endRepeater()

                ->addGroup('opening_hours', ['label' => 'Opening Hours', ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('monday', ['label' => 'Monday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('tuesday', ['label' => 'Tuesday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('wednesday', ['label' => 'Wednesday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('thursday', ['label' => 'Thursday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('friday', ['label' => 'Friday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('saturday', ['label' => 'Saturday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                    ->addText('sunday', ['label' => 'Sunday', 'wrapper' => ['width' => '50%'], 'wpml_cf_preferences' => 1 ])
                ->endGroup()

                ->addGoogleMap('google_map_field', [
                    'label' => 'Google Map Field',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => [],
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'center_lat' => '',
                    'center_lng' => '',
                    'zoom' => '',
                    'height' => '',
                    'wpml_cf_preferences' => 1 // copy
                ])
            ->endGroup();


        return $storeFields->build();
    }
}
