<?php

namespace App\View\Composers\Woocommerce;

use Roots\Acorn\View\Composer;

class ArchiveProduct extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.archive-product',
    ];

    private $term = null;

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'taxonomyTerm' => $this->getTaxonomyTerm(),
            'termDisplayType' => $this->getTermDisplayType(),
            'categoryHasDescription' => $this->categoryHasDescription(),
            // 'category_tree' => $this->getCategoriesTree()
        ];
    }

    private function getTaxonomyTerm()
    {
        if (is_tax()) {
            $this->term = get_queried_object();
            $this->term->thumbnail = wp_get_attachment_url(get_term_meta($this->term->term_id, 'thumbnail_id', true));
        }

        return $this->term;
    }

    private function getTermDisplayType()
    {
        $type = 'products';
        if (is_tax()) {
            $term = $this->term;
            /**
             * @return string empty,products,subcategories,both
             */
            $display_type = get_term_meta($term->term_id, 'display_type', true);
            $type = match ($display_type) {
                '' => 'products',
                'products' => 'products',
                'subcategories' => 'subcategories',
                'both' => 'both',
                default => 'products'
            };
        }

        return $type;
    }

    private function categoryHasDescription()
    {
        if (is_tax()) {
            $term = $this->term;
            if (! empty(category_description($term->term_id))) {
                return true;
            }
        }

        return false;
    }

    private function getCategoriesTree()
    {
        $tree = htwp()->getTermsHierarchy('product_cat') ?? [];

        foreach ($tree as $key => $row) {
            if ($row['term']->slug == 'uncategorized') {
                unset($tree[$key]);
            }
        }

        return $tree;
    }
}
