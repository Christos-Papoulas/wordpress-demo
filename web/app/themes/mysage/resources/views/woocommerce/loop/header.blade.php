<?php
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

?>

<header class="woocommerce-products-header w-full col-span-2">
    <?php
    /**
    * Hook: woocommerce_show_page_title.
    *
    * Allow developers to remove the product taxonomy archive page title.
    *
    * @since 2.0.6.
    */
    if ( apply_filters( 'woocommerce_show_page_title', true ) ) :
        ?>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-body text-xs font-bold lg:text-3xl lg:font-normal mb-0 uppercase woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
        </div>
    </div>

    <?php endif; ?>

    <div class="text-body mt-4 w-full text-sm md:text-xs">
        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @since 1.6.2.
         *
         * @hooked woocommerce_taxonomy_archive_description - 10
         * @hooked woocommerce_product_archive_description - 10
         */
        do_action('woocommerce_archive_description');
        ?>
    </div>
</header>
