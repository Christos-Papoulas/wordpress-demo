<?php

namespace App\Fields\Partials\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Contact extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('contact');

        $fields
            ->addText('address', ['label' => 'Οδός', 'wrapper' => ['width' => '40%']])
            ->addText('city', ['label' => 'Πολή', 'wrapper' => ['width' => '40%']])
            ->addText('address_number', ['label' => 'Αριθμός', 'wrapper' => ['width' => '20%']])
            ->addText('zip_code', ['label' => 'Τ.Κ.', 'wrapper' => ['width' => '30%']])
            ->addText('country', ['label' => 'Χώρα', 'wrapper' => ['width' => '30%']])
            ->addRepeater('emails', ['button_label' => 'Προσθήκη Email', 'wrapper' => ['width' => '50%']])
            ->addEmail('email', ['label' => 'Email'])
            ->endRepeater()
            ->addRepeater('working_hours', ['label' => 'Ωράριο Καταστήματος', 'wrapper' => ['width' => '50%'], 'button_label' => 'Προσθήκη Γραμμής'])
            ->addText('day', ['label' => 'Ημέρα', 'placeholder' => 'Monday'])
            ->addText('hours', ['label' => 'Ωράριο', 'placeholder' => '08:00 - 21:00'])
            ->endRepeater()
            ->addRepeater('phones', ['label' => 'Τηλέφωνα', 'wrapper' => ['width' => '50%'], 'button_label' => 'Προσθήκη Τηλεφώνου'])
            ->addText('number', ['label' => 'Αριθμός'])
            ->addSelect('type', [
                'label' => 'Τύπος',
                'choices' => [
                    'landline' => 'Σταθερό',
                    'mobile' => 'Κινητό',
                ],
            ])
            ->endRepeater()
            ->addRepeater('socials', ['label' => 'Socials', 'wrapper' => ['width' => '50%'], 'button_label' => 'Προσθήκη social'])
            ->addUrl('url', ['label' => 'Σύνδεσμος'])
            ->addSelect('platform', [
                'label' => 'Πλατφόρμα',
                'choices' => [
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'google' => 'Google',
                    'linkedin' => 'Linkedin',
                    'twitter' => 'Twitter',
                    'tiktok' => 'TikTok',
                    'youtube' => 'Youtube',
                ],
            ])
            ->endRepeater();

        return $fields;
    }
}
