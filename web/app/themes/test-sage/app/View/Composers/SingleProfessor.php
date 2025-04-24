<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleProfessor extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single-professor',
    ];

    public function with(): array
    {
        return [
            'programs' => $this->relatedPrograms(),
        ];
    }

    public function relatedPrograms()
    {
        return get_field('related-programs') ?: null;
    }
}
