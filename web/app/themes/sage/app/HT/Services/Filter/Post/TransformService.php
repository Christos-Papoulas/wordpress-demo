<?php

namespace App\HT\Services\Filter\Post;

use BadMethodCallException;

class TransformService
{
    /**
     * Returns post data.
     *
     * @param  \WP_Post  $post
     * @return array
     */
    public static function transformPost($post, $post_type = 'post')
    {

        $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $returnArray = [
            'id' => $post->ID,
            'name' => html_entity_decode(get_the_title($post->ID)),
            'url' => get_permalink($post->ID),
            'content' => html_entity_decode($post->post_content),
            'excerpt' => html_entity_decode(get_the_excerpt($post->ID)),
            'image' => $img_src ? $img_src[0] : wc_placeholder_img_src('full'),
            'datePublished' => get_the_date('c', $post->ID),
            'dateModified' => get_the_modified_date('c', $post->ID),
        ];

        // Add extra properties to the product
        $extra_properties = config('wordpress.'.$post_type.'.extra_post_properties', []);
        foreach ($extra_properties as $key => $property) {
            if ($key == 'call_user_fn') {
                foreach ($property as $name) {
                    $functionName = 'add_property_'.$name;
                    if (method_exists(self::class, $functionName)) {
                        $returnArray[$name] = call_user_func([self::class, $functionName], $post);
                    } else {
                        $reflectionClass = new \ReflectionClass(self::class);
                        $namespace = $reflectionClass->getNamespaceName();
                        throw new BadMethodCallException("Method [$functionName] does not exist on [".$namespace."\TransformService] class.");
                    }
                }
            } elseif ($key == 'acf') {
                foreach ($property as $acfKey) {
                    $returnArray[$acfKey] = ht_get_field($acfKey, $post->ID);
                }
            }
        }

        return $returnArray;
    }

    public static function add_property_categories($post)
    {
        return get_the_category($post->ID);
    }

    public static function add_property_date($post)
    {
        return get_the_date('F jS, Y', $post->ID);
    }

    public static function add_property_excerpt($post)
    {
        return get_the_excerpt($post);
    }

    /**
     * String replace for term names
     *
     * @param  $terms  arrray
     * @return array
     */
    public static function stringReplaceTermNames($terms)
    {
        $terms = array_map(function ($object) {
            $object->name = html_entity_decode($object->name);

            return $object;
        }, $terms);

        return $terms;
    }

    /**
     * Returns term data.
     *
     * @return mixed
     */
    public static function transformTerm($term, $tax, $post_type = 'post')
    {

        $term_obj = get_term($term->term_id, $tax);
        $term->url = get_term_link($term_obj, $tax);
        $term->thumbnail = wp_get_attachment_url(get_term_meta($term->term_id, 'thumbnail_id', true));
        $term->description = $term_obj->description;

        $facetes = config('wordpress.'.$post_type.'.facetes', []);
        $filteredArray = array_filter($facetes, function ($item) use ($tax) {
            return isset($item['taxonomy']) && $item['taxonomy'] == $tax;
        });
        $extra_properties = reset($filteredArray)['extra_term_properties'];
        // error_log(print_r($extra_properties,true));

        // Add extra properties to the term
        foreach ($extra_properties as $key => $property) {
            if ($key == 'call_user_fn') {
                foreach ($property as $name) {
                    $functionName = 'add_property_to_term_'.$name;
                    if (method_exists(self::class, $functionName)) {
                        $term->$name = call_user_func([self::class, $functionName], $term);
                    } else {
                        $reflectionClass = new \ReflectionClass(self::class);
                        $namespace = $reflectionClass->getNamespaceName();
                        throw new BadMethodCallException("Method [$functionName] does not exist on [".$namespace."\TransformService] class.");
                    }
                }
            } elseif ($key == 'acf') {
                foreach ($property as $acfKey) {
                    $term->$acfKey = ht_get_field($acfKey, $tax.'_'.$term->term_id);
                }
            }
        }

        return $term;
    }
}
