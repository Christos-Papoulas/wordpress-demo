<?php

use App\HT\Services\Product\ProductBackInStockService;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * A custom Timeout Order WooCommerce Email class
 *
 * @since 0.1
 *
 * @extends \WC_Email
 */
class WC_Email_Customer_Back_Instock_Product extends WC_Email
{
    /**
     * Set email defaults
     *
     * @since 0.1
     */
    public function __construct()
    {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_back_instock_product';

        // this is the title in WooCommerce Email settings
        $this->title = 'Customer Back in stock Product';

        // this is the description in WooCommerce email settings
        $this->description = 'Customer Back in stock Product Notification emails are sent to the customer when a product gets back in stock.';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = __('Product back in stock', 'sage');
        $this->subject = '🔥🔥🔥 '.__('Product back in stock', 'sage');

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_html = 'emails/customer-back-instock-product.php';
        $this->template_plain = 'emails/plain/customer-back-instock-product.php';

        // Trigger
        // add_action( 'woocommerce_order_status_timeout_notification', array( $this, 'trigger' ) );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // Email is for customer
        $this->customer_email = true;

        // Add custom field to email settings and reorder fields
        $fields_arr = $this->form_fields;
        $fields_arr['email_content_htech'] = [
            'title' => __('Email Content', 'sage'),
            'type' => 'textarea',
            'description' => '{product} - The product name <br> {product_url} - The product url',
            'default' => __('Hello!', 'sage').PHP_EOL.PHP_EOL.__('⏰ Το <strong>{product}</strong> is back in stock', 'sage').PHP_EOL.__('🛒 Take a look at {product_url}', 'sage').PHP_EOL.__("Don't miss the chance!", 'sage'),
            'desc_tip' => false,
        ];

        uksort($fields_arr, function ($a, $b) {
            // Define your custom sorting order here
            $customOrder = [
                'enabled',
                'subject',
                'heading',
                'email_content_htech',
                'additional_content',
                'email_type',
            ];

            // Find the position of keys in the custom order array
            $positionA = array_search($a, $customOrder);
            $positionB = array_search($b, $customOrder);

            // Compare the positions
            return $positionA - $positionB;
        });

        $this->form_fields = $fields_arr;
    }

    /**
     * Get email subject.
     *
     * @since  3.1.0
     *
     * @return string
     */
    public function get_default_subject()
    {
        return __('Product back in stock!', 'sage');
    }

    /**
     * Get email heading.
     *
     * @since  3.1.0
     *
     * @return string
     */
    public function get_default_heading()
    {
        return __('Product back in stock', 'sage');
    }

    /**
     * Determine if the email should actually be sent and setup email merge variables
     *
     * @since 0.1
     *
     * @param  int  $row_id  The row id of the record in the back in stock table
     */
    public function trigger(int $row_id, int $product_id = 0, string $email = '')
    {
        $this->setup_locale();

        $product = wc_get_product($product_id);
        if (! $product) {
            return;
        }

        $this->object = $product;
        $this->recipient = $email;
        $this->placeholders['{order_date}'] = wc_format_datetime(new WC_DateTime);
        $this->placeholders['{product}'] = $product->get_name();
        $this->placeholders['{product_url}'] = '<a href="'.$product->get_permalink().'">'.$product->get_permalink().'</a>';

        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }

        $this->restore_locale();

        ProductBackInStockService::removeRecordFromBackInStockTable($row_id);
    }

    /**
     * Get content html.
     *
     * @return string
     */
    public function get_content_html()
    {
        return wc_get_template_html(
            $this->template_html,
            [
                'product' => $this->object,
                'email_heading' => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
                'email_content' => $this->format_string($this->get_option('email_content_htech')),
            ]
        );
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain()
    {
        return wc_get_template_html(
            $this->template_plain,
            [
                'product' => $this->object,
                'email_heading' => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
                'email_content' => $this->format_string($this->get_option('email_content_htech')),
            ]
        );
    }

    /**
     * Default content to show below main email content.
     *
     * @since 3.7.0
     *
     * @return string
     */
    public function get_default_additional_content()
    {
        return __('Thanks for using {site_url}!', 'woocommerce');
    }
}
