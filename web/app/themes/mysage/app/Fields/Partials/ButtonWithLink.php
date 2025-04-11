<?php

namespace App\Fields\Partials;

use App\Fields\Partials\Button\Link;
use App\Fields\Partials\Button\Style;
use App\Fields\Partials\Button\Text;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class ButtonWithLink extends Partial
{
    /**
     * The partial field group.
     */
    public function fields(): Builder
    {
        $fields = Builder::make('buttonWithLink');

        $fields
            ->addGroup('button', ['label' => 'Button', 'layout' => 'block'])
                ->addFields($this->get(Link::class))
                ->addFields($this->get(Text::class))
                ->addFields($this->get(Style::class))
            ->endGroup();

        return $fields;
    }
}
