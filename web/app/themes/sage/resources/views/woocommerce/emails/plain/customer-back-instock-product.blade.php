<?php
/**
 * Customer back in stock product email
 */
defined('ABSPATH') || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html(wp_strip_all_tags($email_heading));
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
?>

<strong>Product</strong>
is back in stock

<br />

<?php echo get_bloginfo('name'); ?>

.
