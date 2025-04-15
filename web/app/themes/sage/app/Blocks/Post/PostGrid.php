<?php

namespace App\Blocks\Post;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class PostGrid extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Post Grid';

    /**
     * The block view.
     *
     * @var string
     */
    public $view = 'blocks/post/post-grid';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Post Grid block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'ht-category';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path fill="#0042bf" d="m0,10h0c0,5.52,4.34,10,9.7,10h.6c5.36,0,9.7-4.48,9.7-10h0C20,4.48,15.66,0,10.3,0h-.6C4.34,0,0,4.48,0,10Z"/>
        <g>
        <path fill="#fff" d="m6.92,14.51c0,.25-.2.45-.45.44h-1.13c-.25,0-.45-.2-.45-.44V5.49c0-.25.2-.45.45-.44h1.13c.25,0,.45.2.45.44v9.02Z"/>
        <path fill="#fff" d="m15.1,14.51c0,.18-.11.34-.28.41-.06.02-.12.04-.18.04h-1.13c-.25,0-.45-.2-.45-.44v-2.93c0-.32-.26-.58-.58-.57,0,0,0,0,0,0h-3.01c-.25,0-.45-.2-.45-.44v-1.12c0-.25.2-.45.45-.44h3.04c.3,0,.55-.24.55-.55,0,0,0,0,0,0v-2.96c0-.12.05-.23.13-.32.08-.08.2-.13.32-.13h1.13c.06,0,.12.01.18.04.17.07.28.23.28.41v9.02Z"/>
        </g>
    </svg>';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['post', 'grid', 'ht', 'custom'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'edit';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => false,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [
        [
            'name' => 'light',
            'label' => 'Light',
            'isDefault' => true,
        ],
        [
            'name' => 'dark',
            'label' => 'Dark',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'grid' => $this->items(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $postGrid = Builder::make('post_grid');

        $postGrid
            ->addGroup('grid', ['label' => 'Grid'])
                ->addTrueFalse('show_latest', [
                    'label' => 'Show Latest?',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                    'instructions' => 'If show latest is not active, selected posts will be displayed.',
                    'default_value' => 0,
                    'ui' => 1,
                    'wpml_cf_preferences' => 1, // copy
                ])
                ->addPostObject('posts', [
                    'label' => 'Posts',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => [
                        'field' => 'show_latest', 'operator' => '==', 'value' => false,
                    ],
                    'wrapper' => [
                        'width' => '100%',
                    ],
                    'post_type' => ['post'],
                    'taxonomy' => [],
                    'allow_null' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'ui' => 1,
                    'wpml_cf_preferences' => 1, // copy
                ])
                ->addGroup('col_2', ['label' => 'Column 2'])
                    ->addLink('link', [
                        'label' => 'Link',
                        'required' => 1,
                        'return_format' => 'array',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                        'wpml_cf_preferences' => 1, // copy
                    ])
                    ->addImage('img', [
                        'label' => 'Image',
                        'required' => 1,
                        'conditional_logic' => [],
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => [
                            'width' => '50%',
                        ],
                        'library' => 'all',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'wpml_cf_preferences' => 1, // copy
                    ])
                ->endGroup()
            ->endGroup();

        return $postGrid->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        $grid = ht_get_field('grid');

        if ($grid['show_latest']) {
            // suppress_filters false for wpml
            $grid['posts'] = wp_get_recent_posts(['suppress_filters' => false, 'numberposts' => 7], 'OBJECT');
        }

        if ($grid['posts'] == false) {
            $grid['posts'] = [];
        }

        $grid['posts'] = array_filter($grid['posts']);

        return $grid;
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function assets(array $block): void
    {
        //
    }
}
