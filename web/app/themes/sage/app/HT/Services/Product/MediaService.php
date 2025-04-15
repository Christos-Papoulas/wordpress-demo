<?php

namespace App\HT\Services\Product;

use App\HT\Models\Product;

class MediaService
{
    /**
     * Get Product Images. Returns array of urls.
     *
     * @since  1.0.0
     *
     * @param  string  $context  Optionally return all images or only featured image or only the gallery
     * @param  string  $size  The returning size of images
     */
    public static function getProductImages(\WC_Product $product, string $context, string $size = 'full'): mixed
    {
        if ($context == 'all') {
            $main_image = self::getProductMainImage($product, $size);
            $gallery_arr = self::getProductGallery($product, $size);

            if ($main_image) {
                array_unshift($gallery_arr, $main_image);
            }

            return $gallery_arr;
        } elseif ($context == 'featured') {
            return self::getProductMainImage($product, $size);
        } elseif ($context == 'gallery') {
            return self::getProductGallery($product, $size);
        }

        return false;
    }

    /**
     * Get Product Featured Image. Returns the url or false.
     *
     * @since  1.0.0
     *
     * @param  string  $size  The returning size of image
     */
    public static function getProductMainImage(\WC_Product $product, string $size = 'full'): mixed
    {
        $main_image = $product->get_image_id();

        $url = wp_get_attachment_image_url($main_image, $size);

        return ! empty($url) ? $url : wc_placeholder_img_src($size);
    }

    /**
     * Get Product Featured Image. Returns array of urls.
     *
     * @since  1.0.0
     *
     * @param  string  $size  The returning size of images
     */
    public static function getProductGallery(\WC_Product $product, string $size = 'full'): array
    {
        $gallery_ids = $product->get_gallery_image_ids();

        return array_map(fn ($value) => wp_get_attachment_image_url($value, $size), $gallery_ids);
    }

    /**
     * Get Product Images for magiczoom library
     *
     * @since  1.0.0
     *
     * @param  string  $size
     * @param  string  $second_size
     */
    public static function getProductImagesForZoom(\WC_Product $product, $size = 'full', $second_size = false): array
    {
        $main_image = self::getProductMainImageForZoom($product, $size, $second_size);
        $gallery_arr = self::getProductGalleryForZoom($product, $size, $second_size);

        if ($main_image) {
            array_unshift($gallery_arr, $main_image);
        }

        return $gallery_arr;
    }

    /**
     * Get Product Featured Image for magiczoom library
     *
     * @since  1.0.0
     *
     * @param  string  $second_size
     */
    public static function getProductMainImageForZoom(\WC_Product $product, string $size = 'full', $second_size = false): array
    {
        $main_image = $product->get_image_id();
        if (empty($main_image)) {
            return [
                $size => wc_placeholder_img_src($size),
                $second_size => wc_placeholder_img_src($second_size),
            ];
        }

        $img = [$size => wp_get_attachment_image_url($main_image, $size)];

        if ($second_size) {
            $img[$second_size] = wp_get_attachment_image_url($main_image, $second_size);
        }

        return $img;
    }

    /**
     * Get Product Gallery for magiczoom library
     * For magicScroll the best scenario is to have 3 different sizes
     *
     * @since  1.0.0
     *
     * @param  string  $second_size
     */
    public static function getProductGalleryForZoom(\WC_Product $product, string $size = 'full', $second_size = false, $third_size = false): array
    {
        $gallery_ids = $product->get_gallery_image_ids();

        $imgs = array_map(function ($value) use ($size, $second_size, $third_size) {
            $img = [$size => wp_get_attachment_image_url($value, $size)];
            if ($second_size) {
                $img[$second_size] = wp_get_attachment_image_url($value, $second_size);
            }
            if ($second_size) {
                $img[$third_size] = wp_get_attachment_image_url($value, $third_size);
            }

            return $img;
        }, $gallery_ids);

        return $imgs;
    }

    /**
     * Get gallery for product.
     *
     * @since   1.0.0
     */
    public static function getProductGalleryJson(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $product_id = $_POST['product_id'];
        $product = wc_get_product($product_id);
        if ($product) {

            $data = self::getProductImages($product, 'all');
            $featured_img = $data[0];
            array_shift($data);
            wp_send_json_success([
                'featured_img' => $featured_img,
                'gallery' => $data,
            ], 200);

        } else {
            wp_send_json_error(['message' => 'Product not found'], 400);
        }
    }

    /**
     * Search Variation by attribute and get gallery for variation product.
     *
     * @since   1.0.0
     */
    public static function searchVariationAndGetGallery($variationGalleryKey = null): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        $product_id = $_POST['product_id'];
        $attribute_name = $_POST['attribute_name'];
        $attribute_value = $_POST['attribute_value'];

        $product = wc_get_product($product_id);
        if ($product) {

            $variation_ids = $product->get_children();
            $all_variations = [];
            if (is_callable('_prime_post_caches')) {
                _prime_post_caches($variation_ids);
            }
            foreach ($variation_ids as $variation_id) {
                $variation = wc_get_product($variation_id);
                $all_variations[] = [
                    'id' => $variation_id,
                    'attributes' => $variation->get_variation_attributes(),
                ];
            }
            $all_variations = array_values(array_filter($all_variations));

            $variation_id = null;
            foreach ($all_variations as $variation) {
                foreach ($variation['attributes'] as $key => $attr) {
                    if ($attribute_name == $key && $attribute_value == $attr) {
                        $variation_id = $variation['id'];
                        break 2;
                    }
                }
            }

            $variation = wc_get_product($variation_id);
            if ($variation) {

                $gallery = [];
                $variationGalleryKey = 'rtwpvg_images';
                $gallery_ids = get_post_meta($variation_id, $variationGalleryKey, true);
                if ($gallery_ids && is_array($gallery_ids) && ! empty($gallery_ids)) {
                    $gallery = array_map(fn ($value) => wp_get_attachment_image_url($value, 'full'), $gallery_ids);
                }

                wp_send_json_success([
                    'featured_img' => self::getProductMainImageForZoom($variation, 'full', 'woocommerce_single'),
                    'gallery' => $gallery,
                ], 200);
            } else {
                wp_send_json_error(['message' => 'Variation not found'], 400);
            }
        } else {
            wp_send_json_error(['message' => 'Product not found'], 400);
        }
    }

    /**
     * Get gallery for variation product.
     *
     * @since   1.0.0
     */
    public static function getVariationGallery(): void
    {
        $variation_id = $_POST['variation_id'];
        $variation = wc_get_product($variation_id);
        if ($variation) {

            // $gallery = [];
            // $variationGalleryKey = 'rtwpvg_images';
            // $gallery_ids = get_post_meta($variation_id, $variationGalleryKey, true);
            // if($gallery_ids && is_array($gallery_ids) && !empty($gallery_ids)){
            //     $gallery = array_map(fn ($value) => wp_get_attachment_image_url($value, 'full'), $gallery_ids);
            // }

            wp_send_json_success([
                'featured_img' => self::getProductMainImageForZoom($variation, 'full', 'woocommerce_single'),
                'gallery' => self::getProductGallery($variation),
            ], 200);
        } else {
            wp_send_json_error(['message' => 'Variation not found'], 200);
        }
    }

    /**
     * Return product video
     *
     * @since  1.0.0
     */
    public static function getProductVideo(int $product_id): mixed
    {
        return ht_get_field(Product::VIDEO_METAKEY_NAME, $product_id);
    }

    /**
     * Return a second image for product
     *
     * @since  1.0.0
     * 
     * @param \WC_Product $product
     * @param string  $size The returning size of images
     * 
     * @return string
     */
    public static function getASecondImgForProduct(\WC_Product $product, string $size = 'full', $returnPlaceholder = true):string
    {
        $g = $product->get_gallery_image_ids();
        if(empty($g) || !is_array($g)){ return $returnPlaceholder ? wc_placeholder_img_src( $size ) : ''; }

        $second = wp_get_attachment_image_url($g[0], $size);
        if(empty($second)){
            $second = $returnPlaceholder ? wc_placeholder_img_src( $size ) : '';
        }
        return $second;
    }

    /**
     * If variation product does not have featured image, return variation id
     *
     * @since  1.0.0
     * 
     * @param int|string $image_id
     * @param \WC_Product $product
     * 
     * @return int|string
     */
    public static function woocommerce_product_variation_get_image_id( string $image_id, \WC_Product $product ): int|string
    {
        if(!$image_id){ return $product->get_id(); }
        return $image_id;
    }

    /**
     * If product does not have featured image, return product id
     *
     * @since  1.0.0
     * 
     * @param int|string $image_id
     * @param \WC_Product $product
     * 
     * @return int|string
     */
    public static function woocommerce_product_get_image_id( int|string $image_id, \WC_Product $product ): int|string
    {
        if(!$image_id){ return $product->get_id(); }
        return $image_id;
    }

    /**
     * If post does not have featured image and it's a product, return product id
     *
     * @since  1.0.0
     * 
     * @param int|string $thumbnail_id
     * @param \WP_Post $post
     * 
     * @return int|string
     */
    public static function post_thumbnail_id(int|string $thumbnail_id, \WP_Post $post): int|string
    {
        if (!$thumbnail_id && $post && get_post_type($post) === 'product'){
            return $post->ID;
        }
        return $thumbnail_id;
    }


    /**
     * If the attachment_id is not an attachment but it is a product,
     * it means we have returned it from the above functions.
     * Filter the image array with external urls.
     *
     * @since  1.0.0
     * 
     * @param array|bool $image
     * @param null|int $thumbnail_id
     * @param string|array $size
     * @param bool $icon
     * 
     * @return array|bool
     */
    public static function wp_get_attachment_image_src(array|bool $image, null|int $attachment_id, string|array $size, bool $icon): array|bool
    {
        $post = get_post($attachment_id);
        if ($post && get_post_type($post) === 'product') {
  
            $product =  wc_get_product($attachment_id);
            if(!$product){ return $image; }
            $sku = $product->get_sku();
            
            if(config('theme.products.imagesFetchingMethod','native') == 'internal'){
                $image = self::createInternalImageArrayFromSku($sku, $size, $icon);
            }elseif(config('theme.products.imagesFetchingMethod','native') == 'external'){
                $image = self::createExternalImageArrayFromSku($sku, $size, $icon);
            }

        }
        return $image;
    }

    /**
     * Create the image array. Fetch images from uploads folder
     *
     * @since  1.0.0
     * 
     * @param string $sku
     * @param string|array $size
     * @param bool $icon
     * @return array
     */
    private static function createInternalImageArrayFromSku(string $sku, string|array $size, bool $icon): array
    {
        $upload_dir = wp_upload_dir();
        $directory = $upload_dir['basedir'].'/assets';
        $remote_directory = $upload_dir['baseurl'].'/assets';
        $files = scandir($directory);

        if (is_array($files)) {

            $allowed_types = [
                'image/png',
                'image/jpg',
                'image/jpeg',
                'image/webp',
            ];

            if (is_array($files = scandir($directory))) {
                foreach ($files as $file) {
                    // Check if the file matches the SKU pattern
                    if (preg_match('/^'.preg_quote($sku, '/').'\.(.+)$/', $file, $matches)) {
                        $filePath = $directory.'/'.$file;
                        $mime_type = mime_content_type($filePath);
                        if (in_array($mime_type, $allowed_types)) {
                            // Get image dimensions
                            $dimensions = getimagesize($filePath);
                            if ($dimensions) {
                                $width = $dimensions[0];
                                $height = $dimensions[1];

                                return [
                                    $remote_directory.'/'.$file,
                                    $width,
                                    $height,
                                    false,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return [
            wc_placeholder_img_src('full'),
            1200,
            1200,
            false,
        ];
    }

    /**
     * Create the image array. Fetch images from remote url
     *
     * @since  1.0.0
     * 
     * @param string $sku
     * @param string|array $size
     * @param bool $icon
     * @return array
     */
    private static function createExternalImageArrayFromSku(string $sku, string|array $size, bool $icon): array
    {
        $remote_directory = config('theme.products.imagesFetchingURL', '');
        if ($remote_directory === '') {
            return [
                wc_placeholder_img_src('full'),
                1200,
                1200,
                false,
            ];
        }

        $url = $remote_directory.$sku.'/'.$sku.'.jpg';

        return [
            $url,
            1024,
            1024,
            false,
        ];

        // if customer has not images with fixed diamensions, use this logic.
        // $dimensions = getimagesize($url);
        // if (!$dimensions) {
        //     return [
        //         wc_placeholder_img_src('full'),
        //         1200,
        //         1200,
        //         false
        //     ];
        // }

        // $width = $dimensions[0];
        // $height = $dimensions[1];
        // return [
        //     $url,
        //     $width,
        //     $height,
        //     false
        // ];
    }

    /**
     * Create lcp image for product single page
     *
     * @since  1.0.0
     */
    public static function createLcpImage(): void
    {
        if (function_exists('is_product') && is_product()) {
            global $product;
            if ($product) {
                $image = self::getProductMainImage($product, 'full');
                echo '<link rel="preload" as="image" href="'.$image.'">';
            }
        }
    }

    /**
     * Create files/directories.
     * Function copied from woocommerce
     *
     * @since  1.0.0
     */
    public static function createUploadsFolder(): void
    {
        $upload_dir = wp_get_upload_dir();
        $download_method = get_option('woocommerce_file_download_method', 'force');

        $files = [
            [
                'base' => $upload_dir['basedir'].'/assets',
                'file' => 'index.html',
                'content' => '',
            ],
            [
                'base' => $upload_dir['basedir'].'/assets',
                'file' => '.htaccess',
                'content' => $download_method === 'redirect' ? 'Options -Indexes' : 'deny from all',
            ],
        ];

        foreach ($files as $file) {
            $directory = $file['base'];
            $file_path = trailingslashit($directory).$file['file'];

            // Check if directory exists, and if not, create it.
            if (! is_dir($directory)) {
                wp_mkdir_p($directory);
            }

            // Check if file already exists before creating it.
            if (! file_exists($file_path)) {
                $file_handle = @fopen($file_path, 'wb'); // phpcs:ignore
                if ($file_handle) {
                    fwrite($file_handle, $file['content']); // phpcs:ignore
                    fclose($file_handle); // phpcs:ignore
                }
            }
        }
    }
}
