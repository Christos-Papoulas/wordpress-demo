<?php
$config = $args['config'] ?? [];

/**
 * Product taxonomy archive header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 *
 * @version 8.6.0
 */
if (! defined('ABSPATH')) {
    exit;
}

if ($args['passed_args']['unique_id'] == 'ts_woo_main_search') {
	// echo view('partials.breadcrumbs', ['breadcrumbsContainer' => ''])->render();
	echo view('woocommerce/loop/header')->render();
}
