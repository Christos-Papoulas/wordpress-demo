<?php

namespace App\HT\Services\Filter;

class QueryService
{
    public static function add_query_args_for_taxonomies($key, $activeFilter, $query_args)
    {
        foreach ($activeFilter as $taxonomy => $terms) {
            $query_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'id',
                'terms' => array_map(fn ($val) => $val->term_id, $terms),
                'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
            ];
        }
        $query_args['tax_query']['relation'] = 'AND';
        //error_log(print_r($query_args,true));
        return $query_args;
    }

    public static function add_query_args_for_price_range($key, $activeFilter, $query_args)
    {
        $query_args['meta_query'][] = [
            'key' => '_price',
            'value' => [$activeFilter->min, $activeFilter->max],
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ];
        $query_args['meta_query']['relation'] = 'AND';

        return $query_args;
    }

    public static function add_query_args_for_on_sale($key, $activeFilter, $query_args)
    {
        // post_id 0 works as fallback if wc_get_product_ids_on_sale() returns an empty array
        $query_args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());

        return $query_args;
    }

    public static function addOrderBy($orderBy, $defaultOrder, $query_args)
    {
        switch ($orderBy) {
            case 'default':
                $query_args['orderby'] = $defaultOrder['orderby'] ?? 'menu_order title';
                $query_args['order'] = $defaultOrder['order'] ?? 'ASC';
                break;
            case 'menu_order':
                $query_args['orderby'] = 'menu_order title';
                $query_args['order'] = 'ASC';
                break;
            case 'popularity':
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'ASC';
                break;
            case 'date':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
            case 'price':
                $query_args['meta_key'] = '_price';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'ASC';
                break;
            case 'price-desc':
                $query_args['meta_key'] = '_price';
                $query_args['orderby'] = 'meta_value_num';
                $query_args['order'] = 'DESC';
                break;
            default:
                $query_args['orderby'] = $defaultOrder['orderby'] ?? 'menu_order title';
                $query_args['order'] = $defaultOrder['order'] ?? 'ASC';
                break;
        }

        return $query_args;
    }

    public static function add_query_args_for_searchfor($key, $activeFilter, $query_args)
    {
        $query_args['s'] = $activeFilter;

        return $query_args;
    }
}
