<?php

namespace App\HT\Services\Product;

class ProductBackInStockService
{
    public const TABLE_NAME = 'ht_back_in_stock';

    /**
     * Create database table.
     * Now, if you read inside the wp-admin/includes/upgrade.php file, you will notice that dbDelta() uses the preg_match() function in order to retrieve information from the SQL statement.
     * Because of that, you need to be careful when editing it.
     * The official documentation provides more information on this but we’ve highlighted the key points below:
     * --You must have two spaces between the words PRIMARY KEY and the definition of your primary key.
     * --You must use the keyword KEY rather than its synonym INDEX and you must include at least one KEY.
     * --KEY must be followed by a single space, then the key name, then a space, then open parenthesis with the field name, then a closed parenthesis.
     * --Other KEYs than primary, should be given a name. For example:
     * --You must not use any apostrophes or backticks around field names.
     * --Field types must be all lowercase.
     * --SQL keywords, like CREATE TABLE and UPDATE, must be uppercase.
     * --You must specify the length of all fields that accept a length parameter, like int(11) of the id column for example.
     *
     * @see https://codex.wordpress.org/Creating_Tables_with_Plugins#Create_Database_Tables
     */
    public static function createTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix.self::TABLE_NAME;

        $sql = 'CREATE TABLE '.$table_name." (
        id bigint NOT NULL AUTO_INCREMENT,
        product_id bigint NOT NULL,
        email VARCHAR(100) NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY unique_product_email (product_id, email),
        KEY product_id (product_id)
        ) $charset_collate;";

        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /**
     * Add record to the table
     *
     * @param  int  $product_id
     * @param  string  $user_email
     */
    public static function addRecordToBackInStockTable()
    {
        global $wpdb;

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $user_email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';

        if ($product_id && is_email($user_email) && wc_get_product($product_id)) {
            $table_name = $wpdb->prefix.self::TABLE_NAME;

            $data = [
                'product_id' => $product_id,
                'email' => $user_email,
            ];

            $wpdb->insert(
                $table_name,
                $data
            );

            if ($wpdb->insert_id) {
                // TODO: send email to user that confirms his notification
                wp_send_json_success([], 200);
            } else {
                wp_send_json_error([
                    'message' => 'Error adding the record. Please try later.',
                ], 400);
            }
        } else {
            wp_send_json_error([
                'message' => 'Product doesnt exist or email is wrong.',
            ], 400);
        }
    }

    /**
     * Remove record from the table
     */
    public static function removeRecordFromBackInStockTable(int $row_id = 0)
    {
        global $wpdb;
        if ($row_id != 0) {
            $table_name = $wpdb->prefix.self::TABLE_NAME;

            $where = [
                'id' => $row_id,
            ];

            $deleted = $wpdb->delete(
                $table_name,
                $where
            );

            if (! $deleted) {
                return new \WP_Error('database', 'Could not delete record with id:'.$row_id);
            }
        } else {
            return new \WP_Error('record', 'Record does not exist');
        }
    }

    /**
     * Remove records from the table by product id
     */
    public static function removeRecordFromBackInStockTableByProductId(int $product_id = 0)
    {
        global $wpdb;
        if ($product_id != 0) {
            $table_name = $wpdb->prefix.self::TABLE_NAME;

            $where = [
                'product_id' => $product_id,
            ];

            $deleted = $wpdb->delete(
                $table_name,
                $where
            );
        }
    }

    /**
     * Get records from the table by product id
     */
    public static function getRecordsByProductId(int $product_id = 0)
    {
        global $wpdb;
        $results = null;

        if ($product_id) {

            if (wc_get_product($product_id)) {
                $table_name = $wpdb->prefix.self::TABLE_NAME;

                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE product_id = %d",
                        $product_id
                    )
                );

            } else {
                // if product has been deleted remove it from table
                self::removeRecordFromBackInStockTableByProductId($product_id);
            }

            return $results;
        } else {
            return new \WP_Error('record', 'Record does not exist');
        }
    }

    /**
     * Action based on stock status
     *
     * @param  int  $id  product id
     * @param  string  $stockstatus
     *                               $param object $obj product object
     */
    public static function action_based_on_stock_status($id, $stockstatus, $obj = '')
    {
        if ($stockstatus == 'instock') {

            if (! $obj) {
                $obj = wc_get_product($id);
            }

            if ($obj && ($obj->is_type('variation') || $obj->is_type('variable'))) {

                if ($obj->get_type() == 'variation') {
                    // error_log('=========variation stock updated');
                    $records = self::getRecordsByProductId($id);
                    self::sendEmails($records);
                }
            } else {
                $records = self::getRecordsByProductId($id);
                self::sendEmails($records);
            }
        } else {
            // error_log('=========product/variation stock updated but not instock');
        }
    }

    /**
     * Send emails to users for the product that is back in stock
     *
     * @param  array  $records
     */
    public static function sendEmails($records)
    {

        if (is_wp_error($records)) {
            error_log($records->get_error_message());

            return;
        }

        if (! empty($records)) {
            // error_log(print_r($records, true));
            foreach ($records as $record) {
                // if email is valid is checked in the addRecordToBackInStockTable method
                // if product has been deleted remove it from table is checked in the getRecordsByProductId method
                // if product exists also is checked at the email trigger function
                if (! empty($record->email) && (int) $record->product_id != 0) {
                    WC()->mailer()->emails['WC_Email_Customer_Back_Instock_Product']->trigger((int) $record->id, (int) $record->product_id, $record->email);
                }
            }
        }
    }
}
