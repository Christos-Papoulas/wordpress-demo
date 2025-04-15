<?php

namespace App\HT\Services;
use Illuminate\Support\Collection;
use App\HT\Services\Product\ProductService;

class Wishlist
{
    private const WISHLIST_METAKEY_NAME = 'ht_wishlist';
    public array $list = [];
    public $settings;

    public function __construct()
    {
        $myWishlist = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $myWishlist = get_user_meta($user->ID, self::WISHLIST_METAKEY_NAME, true);

            if ($myWishlist instanceof Wishlist && property_exists($myWishlist, 'list')) {
                $this->list = $myWishlist->list;
                return;
            }
        } 
    }

    /**
     * Check if product is in wishlist.
     * 
     * @param int $post_id Product ID
     * 
     * @return bool
     */
    public function checkIfProductIsInWihslist($post_id):bool
    {
        return in_array($post_id, $this->list);
    }

    /**
     * Insert product to user's wishlist.
     * 
     * @param int $post_id Product ID
     * 
     * @return array
     */
    private function insert(int $post_id):array
    {
        
        $user = wp_get_current_user();
        if ($user->ID != 0) {
           
            if (!in_array($post_id, $this->list)) {
                $this->list[] = $post_id;
                if(update_user_meta($user->ID, self::WISHLIST_METAKEY_NAME, $this)){
                    return [
                        'success' => true,
                        'data' => [
                            'wishlist' => $this,
                            'inWishlist' => $this->checkIfProductIsInWihslist($post_id)
                        ]
                    ];

                }else{
                    return [
                        'success' => false,
                        'data' => [
                            'message' => __('Failed to update','sage'),
                        ]
                    ];
                }
            }else{
                return [
                    'success' => false,
                    'data' => [
                        'message' => __('Already in wishlist','sage'),
                    ]
                ];
            }
        }

        return [
            'success' => false,
            'data' => [
                'message' => __('Please log in to use the wishlist.','sage'),
            ]
        ];

    }

    /**
     * Remove product from user's wishlist.
     * 
     * @param int $post_id Product ID
     * 
     * @return array
     */
    private function remove(int $post_id):array
    {
        $user = wp_get_current_user();
        if ($user->ID != 0) {
 
            $this->list = array_values(
                array_filter(
                    $this->list,
                    fn($item) => $item !== $post_id
                )
            );

            if(update_user_meta($user->ID, self::WISHLIST_METAKEY_NAME, $this)){

                $return = [
                    'success' => true,
                    'data' => [
                        'wishlist' => $this,
                        'inWishlist' => $this->checkIfProductIsInWihslist($post_id)
                    ]
                ];

                // get products from ids for the list
                if(isset($_POST['get_products']) && filter_var($_POST['get_products'], FILTER_VALIDATE_BOOLEAN)){
                    $return['data']['products'] = $this->getListItemsFromIds($this->list);
                }

                return $return;

            }else{
                return [
                    'success' => false,
                    'data' => [
                        'message' => __('Failed to update','sage'),
                    ]
                ];
            }
        }

        return [
            'success' => false,
            'data' => [
                'message' => __('Please log in to use the wishlist.','sage'),
            ]
        ];
    }

    /**
     * Clear user's wishlist.
     * 
     * @return array
     */
    private function clear():array
    {
        $user = wp_get_current_user();
        if ($user->ID != 0) {
            $this->list = [];
            if(update_user_meta($user->ID, self::WISHLIST_METAKEY_NAME, $this)){
                return [
                    'success' => true,
                    'data' => [
                        'wishlist' => $this,
                        'inWishlist' => false,
                    ]
                ];
            }
        } 

        return [
            'success' => false,
            'data' => [
                'message' => __('Please log in to use the wishlist.','sage'),
            ]
        ];
    }

   	/**
	 * Edit user's wishlist.
	 * Returns JSON response.
     *
	 * @since   1.0.0
	 * 
     * @param   int $post_id Product ID
     * @param   string $db_action Database action
     * @return  void
	 */
    public function edit():void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        $post_id = $_POST['post_id'] ?? null;
        $db_action = $_POST['db_action'] ?? null;
        
        if(empty($post_id) || empty($db_action)){ 
            wp_send_json_error([
                'message' => 'Not enough data',
            ], 400);
        }

        if ($db_action == 'insert') {
            $return = $this->insert($post_id);
        } elseif ($db_action == 'remove') {
            $return = $this->remove($post_id);
        } elseif ($db_action == 'clear') {
            $return = $this->clear();
        }else{
            wp_send_json_error([
                'message' => 'Action is not supported',
            ], 400);
        }

        if($return['success']){
            wp_send_json_success($return['data'], 200);
        } else {
            wp_send_json_error($return['data'], 400);
        }
    }

    /**
	 * Gets list items by ids.
     *
	 * @since   1.0.0
	 * 
     * @return  Collection
	 */
    public function getListItemsFromIds(array $post_ids = []):Collection
    {
        if(empty($post_ids)){
            return collect([]);
        }

        $items = array_map( fn($pid) => wc_get_product($pid),$post_ids);
        // remove false values if product not found, or product is hidden from catalog
        $items = array_filter($items, function($product) {
            return $product !== false && $product !== null && $product->get_catalog_visibility() !== 'hidden';
        });

        $items = $this->TransformItems(collect($items));
        return $items;
    }
    
   	/**
	 * Gets list items by ids.
	 * Returns JSON response.
     *
	 * @since   1.0.0
	 * 
     * @return  void
	 */
    public function getListItemsFromIdsJSON():void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $post_ids = json_decode(stripslashes($_POST['post_ids']));
        wp_send_json_success([
            'items' => $this->getListItemsFromIds($post_ids)
        ], 200);
    }
    
    /**
	 * Transform items.
     *
	 * @since   1.0.0
	 * 
     * @return  Collection
	 */
    private function TransformItems($collection):Collection
    {
        return $collection->transform(function ($item) {
            return ProductService::createProductCardData($item);
        });
    }
}
