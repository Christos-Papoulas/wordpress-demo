<?php

namespace App\HT\Services;

class WordpressService
{
    /**
     * Returns the entire taxonomy hierarchy as a nested array. Provides lvls and children.
     *
     * @return array
     */
    public static function getTermsHierarchy($taxonomy, $parent = 0, $level = 0, $max_level = 0)
    {
        $terms = get_terms(['taxonomy' => $taxonomy, 'parent' => $parent, 'hide_empty' => false]);
        $hierarchy = [];
        if (! empty($terms)) {
            foreach ($terms as $term) {
                $hierarchy[] = [
                    'term' => $term,
                    'level' => $level,
                    'children' => ($max_level == 0 || $max_level > $level + 1) ? self::getTermsHierarchy($taxonomy,
                        $term->term_id, $level + 1, $max_level) : [],
                ];
            }
        }

        return $hierarchy;
    }
}
