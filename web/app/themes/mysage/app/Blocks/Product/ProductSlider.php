<?php

namespace App\Blocks\Product;

use App\HT\Services\Product\CompareProductService;
use App\HT\Services\Product\ProductService;
use App\HT\Services\Product\RecentlyViewProductsService;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ProductSlider extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Product Slider';

    /**
     * The block view.
     *
     * @var string
     */
    public $view = 'blocks/product/product-slider';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Product Slider block.';

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
    public $keywords = ['product', 'slider', 'ht', 'custom'];

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
            'slider' => $this->items(),
            // 'myCompareList' => CompareProductService::getCompareProductList(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $productSlider = Builder::make('product_slider');

        $productSlider
            ->addGroup('slider', ['label' => 'Slider'])
            ->addWysiwyg('title', [
                'label' => 'Title',
                'required' => 0,
                'toolbar' => 'ht_title',
                'wrapper' => [
                    'width' => '100%',
                ],
                'wpml_cf_preferences' => 2, // translate
            ])
            ->addSelect('display_type', [
                'label' => 'Τύπος',
                'choices' => [
                    ['default' => 'Επιλέξτε Προϊόντα'],
                    ['latest' => 'Πρόσφατα'],
                    ['recenlty_viewed' => 'Recently Viewed'],
                ],
                'default_value' => 'default',
                'wpml_cf_preferences' => 1, // copy
            ])
            ->addPostObject('products', [
                'label' => 'Products',
                'instructions' => '',
                'required' => 0,

                'wrapper' => [
                    'width' => '100%',
                ],
                'post_type' => ['product'],
                'taxonomy' => [],
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'id',
                'ui' => 1,
                'wpml_cf_preferences' => 1, // copy
            ])->conditional('display_type', '!=', 'latest')->and('display_type', '!=', 'recenlty_viewed')
            ->endGroup();

        return $productSlider->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        $slider = ht_get_field('slider');
        if ($slider['display_type'] == 'latest') {
            $slider['products'] = ProductService::getProducts(
                [
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'posts_per_page' => 8,
                ]
            );
        } elseif ($slider['display_type'] == 'recenlty_viewed') {
            $slider['products'] = array_map(fn ($pid) => wc_get_product($pid), RecentlyViewProductsService::get());
        } else {

            if ($slider['products'] == false || empty($slider['products'])) {
                $slider['products'] = [];
            }

            $slider['products'] = array_filter($slider['products']);

            // WPML
            if (defined('ICL_SITEPRESS_VERSION')) {
                $slider['products'] = array_map(fn ($pid) => apply_filters('wpml_object_id', $pid, 'post'), $slider['products']);
            }
            $slider['products'] = array_map(fn ($pid) => wc_get_product($pid), $slider['products']);
        }

        if ($slider['products'] == false) {
            $slider['products'] = [];
        } else {
            $slider['products'] = array_filter($slider['products'], function ($product) {
                return $product && $product !== null && $product->get_catalog_visibility() !== 'hidden';
            });
        }

        return $slider;
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
