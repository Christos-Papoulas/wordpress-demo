<?php

/*
Plugin Name: Word filter plugin
Description: Word filter plugin
Version: 1.0.0
Author: Tralala Lala
Text Domain: word-filter-domain
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
	exit;
}


require_once __DIR__ . '/WordFilterPlugin.php';

$wordFilterPlugin = new WordFilterPlugin();
