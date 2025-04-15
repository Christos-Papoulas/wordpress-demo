<?php

namespace App\Fields\Partials\Options;

use App\Fields\Partials\Color\BackgroundColor;
use App\Fields\Partials\Color\TextColor;
use App\Fields\Partials\Color\TextHoverColor;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Header extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('header');

        $fields
            ->addGroup('header', ['label' => 'Header'])
                ->addImage('logo', [
                    'label' => 'Logo',
                    'required' => 1,
                    'wrapper' => [
                        'width' => '25%',
                    ],
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ])
                ->addImage('sticky_logo', [
                    'label' => 'Mobile Logo',
                    'required' => 1,
                    'wrapper' => [
                        'width' => '25%',
                    ],
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ])

                ->addFields($this->get(BackgroundColor::class))
                ->addFields($this->get(TextColor::class))
                ->addFields($this->get(TextHoverColor::class))
       
                // ->addGroup('messages', ['label' => 'Messages', 'wrapper' => ['width' => '100%']])
                //     ->addNumber('duration', [
                //         'label' => 'Duration',
                //         'instructions' => 'Adjust the speed using the duration number. More slides need bigger duration to keep the same speed.',
                //         'required' => 0,
                //         'wrapper' => [
                //             'width' => '25%',
                //         ],
                //         'default_value' => '10',
                //         'append' => 'seconds',
                //         'min' => '10',
                //         'max' => '',
                //         'step' => '1',
                //     ])
                //     ->addRepeater('slides', ['button_label' => 'Προσθήκη Γραμμής', 'wrapper' => ['width' => '75%']])
                //         ->addWysiwyg('message', [
                //             'label' => 'Message',
                //             'instructions' => 'Slider will always show whole content in 60s. Adjust your content.',
                //             'required' => 0,
                //             'toolbar' => 'ht',
                //             'wrapper' => [
                //                 'width' => '100%',
                //             ],
                //         ])
                //     ->endRepeater()
                // ->endGroup()
            ->endGroup();

        return $fields;
    }
}
