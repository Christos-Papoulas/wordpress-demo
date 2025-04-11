<?php

namespace App\Fields\Partials\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Benefits extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('benefits');

        $fields
            ->addGroup('benefits', ['label' => 'Benefits'])
            ->addRepeater('benefits', [
                'button_label' => 'Προσθήκη Benefit',
                'conditional_logic' => [
                    'field' => 'use_global',
                    'operator' => '==',
                    'value' => '0',
                ],
                'wrapper' => ['width' => '100%'],
            ])
            ->addText('title', [
                'label' => 'Title',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                ],
            ])
            ->addText('content', [
                'label' => 'Content',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                ],
            ])
            ->addLink('link', [
                'label' => 'Link',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                ],
                'return_format' => 'array',
            ])
            ->addImage('img', [
                'label' => 'Image',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                ],
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ])
            ->endRepeater()
            ->endGroup();

        return $fields;
    }
}
