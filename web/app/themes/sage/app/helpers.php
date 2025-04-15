<?php

if (! function_exists('ht_get_field')) {
    /**
     * A wrapper function that returns empty strings as null when calling acf's get_field()
     */
    function ht_get_field(string $selector, mixed $post_id = false, bool $format_value = true)
    {
        $data = get_field($selector, $post_id, $format_value);

        return ($data === '') ? null : $data;
    }
}

if (! function_exists('remove_greek_accents')) {
    function remove_greek_accents($string)
    {
        $arr1 = ['ά', 'έ', 'ή', 'ί', 'ό', 'ύ', 'εί', 'οί', 'αί', 'ώ'];
        $arr2 = ['α', 'ε', 'η', 'ι', 'ο', 'υ', 'ει', 'οι', 'αι', 'ω'];

        return str_replace($arr1, $arr2, $string);
    }
}

if (! function_exists('implode_recursive')) {
    function implode_recursive(string $separator, array $array): string
    {
        $string = '';
        foreach ($array as $i => $a) {
            if (is_array($a)) {
                $string .= implode_recursive($separator, $a);
            } else {
                $string .= $a;
                if ($i < count($array) - 1) {
                    $string .= $separator;
                }
            }
        }

        return $string;
    }
}
