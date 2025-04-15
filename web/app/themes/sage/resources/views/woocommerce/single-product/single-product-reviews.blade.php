<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 *
 * @version 4.3.0
 */
defined('ABSPATH') || exit;

global $product;

if (! comments_open()) {
    return;
}
$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average = $product->get_average_rating();
$count = $product->get_review_count();

?>

<div id="reviews" class="woocommerce-Reviews">
    <div class="flex items-center justify-between space-x-4">
        <div class="relative flex">
            @include('woocommerce.single-product.rating')
            <div class="px-2 leading-[22px]">
                <div class="">
                    <span class="text-lg text-black">{{ $average }}</span>
                    <span class="px-2 text-slate-500">/</span>
                    <span class="text-slate-500">5</span>
                </div>
                <div class="text-xs leading-none">
                    <span>{{ $review_count }}</span>
                    <span class="px-2 text-slate-500">reviews</span>
                </div>
            </div>
        </div>
        <div class="">
            <button
                id="toggle-review-form"
                class="block w-full border-4 border-black bg-gray-600 px-8 pt-3 pb-3 text-center text-xs text-black uppercase"
            >
                Write a review
            </button>
        </div>
    </div>

    @if (! empty($product_gallery))
        <div class="grid grid-cols-3 gap-x-4 py-4">
            @foreach ($product_gallery as $key => $img)
                @if ($key < 3)
                    @php
                        $extension = pathinfo($img, PATHINFO_EXTENSION);
                    @endphp

                    <div class="@if($extension != 'jpg') p-2 @endif flex border-4 border-black">
                        <img src="{{ $img }}" alt="" class="object-cover" />
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div id="product_comments" class="border-t-2 border-black">
        {{-- {{dd($product_comments)}} --}}
        @foreach ($product_comments as $comment)
            <div class="border-b border-black py-3">
                <div class="">
                    <span class="pr-2">{{ $comment->comment_author }}</span>
                    <span>{{ comment_date('d F', $comment->comment_ID) }}</span>
                </div>
                <p class="mb-0">
                    {{ $comment->comment_content }}
                </p>
            </div>
        @endforeach
    </div>

    <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

    <div id="review_form_wrapper">
        <div id="review_form" class="hidden">
            <?php
            $commenter = wp_get_current_commenter();
            $comment_form = [
                /* translators: %s is product title */
                // 'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                /* translators: %s is product title */
                'title_reply_to' => esc_html__('Leave a Reply to %s', 'woocommerce'),
                'title_reply_before' => '<span id="reply-title" class="comment-reply-title">',
                'title_reply_after' => '</span>',
                'comment_notes_after' => '',
                'label_submit' => esc_html__('Submit', 'woocommerce'),
                'logged_in_as' => '',
                'comment_field' => '',
            ];

            $name_email_required = (bool) get_option('require_name_email', 1);
            $fields = [
                'author' => [
                    'label' => __('Name', 'woocommerce'),
                    'type' => 'text',
                    'value' => $commenter['comment_author'],
                    'required' => $name_email_required,
                ],
                'email' => [
                    'label' => __('Email', 'woocommerce'),
                    'type' => 'email',
                    'value' => $commenter['comment_author_email'],
                    'required' => $name_email_required,
                ],
            ];

            $comment_form['fields'] = [];

            foreach ($fields as $key => $field) {
                $field_html = '<p class="comment-form-'.esc_attr($key).'">';
                $field_html .= '<label for="'.esc_attr($key).'">'.esc_html($field['label']);

                if ($field['required']) {
                    $field_html .= '&nbsp;<span class="required">*</span>';
                }

                $field_html .= '</label><input id="'.esc_attr($key).'" name="'.esc_attr($key).'" type="'.esc_attr($field['type']).'" value="'.esc_attr($field['value']).'" size="30" '.($field['required'] ? 'required' : '').' /></p>';

                $comment_form['fields'][$key] = $field_html;
            }

            $account_page_url = wc_get_page_permalink('myaccount');
            if ($account_page_url) {
                /* translators: %s opening and closing link tags respectively */
                $comment_form['must_log_in'] = '<p class="must-log-in">'.sprintf(esc_html__('You must be %1$slogged in%2$s to post a review.', 'woocommerce'), '<a href="'.esc_url($account_page_url).'">', '</a>').'</p>';
            }

            if (wc_review_ratings_enabled()) {
                $comment_form['comment_field'] = '<div class="comment-form-rating my-5"><label for="rating">'.esc_html__('Your rating', 'woocommerce').(wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '').'</label><select name="rating" id="rating" required>
                        						<option value="">'.esc_html__('Rate&hellip;', 'woocommerce').'</option>
                        						<option value="5">'.esc_html__('Perfect', 'woocommerce').'</option>
                        						<option value="4">'.esc_html__('Good', 'woocommerce').'</option>
                        						<option value="3">'.esc_html__('Average', 'woocommerce').'</option>
                        						<option value="2">'.esc_html__('Not that bad', 'woocommerce').'</option>
                        						<option value="1">'.esc_html__('Very poor', 'woocommerce').'</option>
                        					</select></div>';
            }

            $comment_form['comment_field'] .= '<p class="mt-5 comment-form-comment"><label for="comment">'.esc_html__('Your review', 'woocommerce').'&nbsp;<span class="required">*</span></label><textarea class="mt-1 w-full" id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

            comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
            ?>
        </div>
    </div>

    <?php else : ?>

    <p class="woocommerce-verification-required">
        <?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?>
    </p>

    <?php endif; ?>

    <div class="clear"></div>
</div>
