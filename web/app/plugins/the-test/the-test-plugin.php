<?php

/*
Plugin Name: The Test Plugin
Description: The Test Plugin
Version: 1.0.0
Author: Tralala Lala
*/

add_filter('the_content', function ($content) {
	if (is_single() && is_main_query()) {
		$content .= '<p>The Test Plugin</p>';
	}

	return $content;
});
