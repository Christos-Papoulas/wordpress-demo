<?php

namespace App\Fields\Partials\Options;

use App\Fields\Partials\Color\BackgroundColor;
use App\Fields\Partials\Color\TextColor;
use App\Fields\Partials\Color\TextHoverColor;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Footer extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('footer');

        $fields
            ->addGroup('footer', ['label' => 'Footer'])
            ->addImage('logo', [
                'label' => 'Logo',
                'required' => 0,
                'wrapper' => [
                    'width' => '15%',
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
            ->addText('copyright_text', [
                'label' => 'Copyright Text',
                'required' => 1,
                'wrapper' => [
                    'width' => '100%',
                ],
            ])
            ->endGroup();

        return $fields;
    }
}
