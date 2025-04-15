<?php

namespace App\Fields;

use App\Fields\Partials\Container;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class Page extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $page = Builder::make('Page Options', ['position' => 'side']);

        $page
            ->setLocation('post_type', '==', 'page')
            ->and('page_type', '!=', 'front_page');

        $page
            ->addGroup('page_breadcrumbs', ['label' => 'Breadcrumbs', 'wpml_cf_preferences' => 1])
            ->addTrueFalse('enabled', [
                'label' => 'Show Breadcrumbs',
                'default_value' => 1,
                'wpml_cf_preferences' => 1, // copy
            ])
            ->addFields($this->get(Container::class))
            ->endGroup()
            ->addGroup('page_title', ['label' => 'Page Title', 'wpml_cf_preferences' => 1])
            ->addTrueFalse('enabled', [
                'label' => 'Show Title',
                'default_value' => 1,
                'wpml_cf_preferences' => 1, // copy
            ])
            ->addFields($this->get(Container::class))
            ->endGroup();

        return $page->build();
    }
}
