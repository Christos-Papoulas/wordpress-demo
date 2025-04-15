<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class MenuItem extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $menuItem = Builder::make('menu_item');

        $menuItem
            ->setLocation('nav_menu_item', '==', 'all');

        $menuItem
            ->addImage('img', [
                'label' => 'Image',
                'required' => 0,
                'conditional_logic' => [],
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ]);

        return $menuItem->build();
    }
}
