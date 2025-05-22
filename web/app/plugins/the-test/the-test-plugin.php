<?php

/*
Plugin Name: The Test Plugin
Description: The Test Plugin
Version: 1.0.0
Author: Tralala Lala
*/

if ( is_admin() ) {
	require_once __DIR__ . '/WordCountAndTime.php';

	$wordCountAndTime = new WordCountAndTime();
}
