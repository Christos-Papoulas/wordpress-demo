<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;
use App\Fields\Partials\ButtonWithLink;

class ImageAndContentBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Image and Content Block';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Image and Content Block.';

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
    public $keywords = [];

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
        'anchor' => true,
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
        ]
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
            'section' => $this->items(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $imageAndContentBlock = Builder::make('image_content_block');

        $imageAndContentBlock
        ->addGroup('image_content_block', ['label' => 'ImageAndContentBlock'])
                ->addImage('img', [
                    'label' => 'Image',
                    'required' => 1,
                    'conditional_logic' => [],
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'wrapper' => [
                        'width' => '20%',
                    ],
                    'library' => 'all',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'wpml_cf_preferences' => 1, // copy
                ])
                ->addTrueFalse('img_as_bg', [
                    'label' => 'Show Whole Image?',
                    'instructions' => 'By default image will be centered and the content will define the height of the image.',
                    'required' => 0,
                    'wrapper' => [
                        'width' => '20%',
                    ],
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => 'No',
                    'ui_off_text' => 'Yes',
                    'wpml_cf_preferences' => 1, // copy
                ])
                ->addTrueFalse('img_left', [
                    'label' => 'Image Position',
                    'instructions' => '',
                    'required' => 0,
                    'wrapper' => [
                        'width' => '20%',
                    ],
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => 'Left',
                    'ui_off_text' => 'Right',
                    'wpml_cf_preferences' => 1, // copy
                ])
                ->addWysiwyg('title', [
                    'label' => 'Title',
                    'required' => 0,
                    'toolbar' => 'ht_title',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                    'wpml_cf_preferences' => 2 // translate
                ])
                ->addWysiwyg('content', [
                    'label' => 'content',
                    'required' => 1,
                    'toolbar' => 'ht',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                    'wpml_cf_preferences' => 2 // translate
                ])
                ->addFields($this->get(ButtonWithLink::class))
        ->endGroup();

        return $imageAndContentBlock->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return ht_get_field('image_content_block');
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
