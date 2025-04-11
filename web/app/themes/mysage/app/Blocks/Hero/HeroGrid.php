<?php

namespace App\Blocks\Hero;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class HeroGrid extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Hero Block';

    /**
     * The block view.
     *
     * @var string
     */
    public $view = 'blocks/hero/hero-grid';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Hero block.';

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
    public $keywords = ['ht','custom','hero'];

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
            'hero' => $this->items(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $hero = new FieldsBuilder('hero');

        $hero
            ->addGroup('hero', ['label' => 'Hero'])
                ->addRepeater('blocks', [
                    'button_label' => 'Προσθήκη Γραμμής',
                    'wrapper' => ['width' => '100%'],
                    'min' => 7,
                    'max' => 7,
                    ])
                    ->addText('title', [
                        'label' => 'Title', 
                        'wrapper' => ['width' => '40%'], 
                        'wpml_cf_preferences' => 2 // translate
                    ])
                    ->addSelect('block_type', [
                        'label' => 'Style',
                        'choices' => ['img','video'],
                        'default' => 'img',
                        'wpml_cf_preferences' => 1 // copy
                    ])
                    ->addImage('img', [
                        'label' => 'Image',
                        'required' => 0,
                        // 'conditional_logic' => [
                        //     'field' => 'block_type', 'operator' => '!=', 'value' => 'video'
                        // ], 
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
                        'wpml_cf_preferences' => 2 // translate
                    ])
                    ->addUrl('video', [
                        'label' => 'Video URL',
                        'instructions' => '',
                        'required' => 0,
                        // 'conditional_logic' => [
                        //     'field' => 'block_type', 'operator' => '==', 'value' => 'video'
                        // ], 
                        'wrapper' => [
                            'width' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'wpml_cf_preferences' => 2 // translate
                    ])
                    ->addLink('link', [
                        'label' => 'Link',
                        'required' => 1,
                        'return_format' => 'array',
                        'wrapper' => [
                            'width' => '50%'
                        ],
                        'wpml_cf_preferences' => 2 // translate
                    ])
                ->endRepeater()
            ->endGroup();

        return $hero->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return ht_get_field('hero');
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        //
    }
}
