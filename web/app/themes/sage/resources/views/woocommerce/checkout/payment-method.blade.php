<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use App\HT\Services\PaymentService;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<?php $gateway->payment_fields(); ?>

            @if($gateway->id == 'caod')
                @php
                    $banks = [
                        'Alpha Bank' => __('Alpha Bank','sage'),
                        'Εθνική Τράπεζα' => __('Εθνική Τράπεζα','sage'),
                        'Eurobank' => __('Eurobank','sage'),
                        'Άλλη Τράπεζα' => __('Άλλη Τράπεζα','sage'),
                        'Ticket Restaurant Prepaid' => __('Ticket Restaurant Prepaid','sage'),
                    ]
                @endphp
                @foreach($banks as $bank => $bankLabel)
                    <div
                    x-data="{ selectedBank:{{ $bank }} }" class="mt-3 ml-5">

                        <div x-on:click="selectedBank = {{ $bank }}" class="flex justify-start items-center gap-2">
                            <input type="radio" id="{{ 'caod_' . $bank }}" name="{{ PaymentService::POS_INPUT_NAME }}" value="{{ $bank }}" class="shrinl-0 w-4 h-4" @if($bank == 'Alpha Bank') checked @endif />
                            <label class="cursor-pointer" for="{{ 'caod_' . $bank }}">{{ $bankLabel }}</label>
                        </div>

                    </div>
                @endforeach
            @endif

		</div>
	<?php endif; ?>
</li>
