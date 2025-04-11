<?php

namespace App\Fields\Partials;

use App\Fields\Partials\Color\BackgroundColor;
use App\Fields\Partials\Color\TextColor;
use App\Fields\Partials\Color\TextHoverColor;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class Topbar extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('topbar');

        $fields
            ->addGroup('topbar', ['label' => 'Topbar'])
            ->addFields($this->get(BackgroundColor::class))
            ->addFields($this->get(TextColor::class))
            ->addFields($this->get(TextHoverColor::class))
            ->endGroup();

        return $fields;
    }
}
