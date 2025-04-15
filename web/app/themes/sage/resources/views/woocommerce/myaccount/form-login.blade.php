<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<style>
	/* 
	@media screen AND (min-width:1280px){
		.htech-breadcrumb,.page-header{
		padding-inline: 1rem;
	}
	}
	*/
	 .htech-breadcrumb li, .htech-breadcrumb li a{
		color:#fff!important;
	}
	ul.woocommerce-error, .woocommerce-error {
		color:#fff!important;
	}
</style>
	<div 
	x-data="{tabOpened: 1}"
	class="flex flex-col lg:flex-row justify-between ht-container-no-max-width bg-primary text-white !px-0">
		<div class="xl:mx-auto w-full ">
			<div>
				@include('partials.breadcrumbs')
				<div class="ht-container-no-max-width page-header">
					<h1 class="flex text-xs font-bold xl:text-3xl xl:font-normal mb-8 xl:mb-12 uppercase">{!! get_the_title() !!}</h1>
				</div>
			</div>
			<div>
				<div class="ht-container-medium !px-0 ">
					<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
				</div>

				<div class="flexw-full px-5 xl:px-10 pb-14">
					<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

					<div class="w-full flex flex-wrap gap-8 justify-center" id="customer_login">

						<div x-show="tabOpened == 1" class="bg-white xl:!block">

					<?php endif; ?>

					<form class="woocommerce-form woocommerce-form-login login h-full flex flex-col justify-between text-black py-8 px-4 md:px-6 w-96" method="post">

						<div>
							<div class="flex gap-3">
								<h2 x-on:click="tabOpened = 1" :class="tabOpened == 1 ? 'underline' : 'text-gray-400'" class="cursor-pointer xl:cursor-default xl:!text-body xl:!no-underline text-xs text-body font-bold uppercase ">{{ __( 'Login', 'woocommerce' ) }}</h2>
								<h2 x-on:click="tabOpened = 2" :class="tabOpened == 2 ? 'underline' : 'text-gray-400'" class="cursor-pointer xl:cursor-default xl:!text-body xl:!no-underline text-xs text-body font-bold uppercase xl:hidden">{{ __( 'Register', 'woocommerce' ) }}</h2>
							</div>

							<?php do_action( 'woocommerce_login_form_start' ); ?>

							<p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text" name="username" id="username" autocomplete="username" placeholder="user@mail.com" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
							</p>
							<p x-data="{show:false}" class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide relative">
								<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input :type="show ? 'text' : 'password'" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="password"  autocomplete="current-password" />

								<span class="absolute right-0 bottom-0 transfrom -translate-y-[50%] cursor-pointer"  x-on:click="show = !show" >
									<svg x-show="!show" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M8.99977 15.5757C9.82752 15.5757 10.5835 15.4855 11.273 15.3298L9.73564 13.7924C9.49677 13.8108 9.25439 13.8257 8.99977 13.8257C4.31764 13.8257 2.50377 10.4604 2.06452 9.45066C2.39435 8.71461 2.83981 8.03608 3.38402 7.44078L2.16077 6.21753C0.815018 7.67616 0.304893 9.14528 0.295268 9.17416C0.234911 9.35385 0.234911 9.54834 0.295268 9.72803C0.313643 9.78578 2.32089 15.5757 8.99977 15.5757ZM8.99977 3.32566C7.39239 3.32566 6.07202 3.67216 4.97127 4.18403L1.74339 0.957031L0.506143 2.19428L16.2561 17.9443L17.4934 16.707L14.5893 13.8029C16.8765 12.0958 17.6929 9.76478 17.7051 9.72803C17.7655 9.54834 17.7655 9.35385 17.7051 9.17416C17.6859 9.11553 15.6786 3.32566 8.99977 3.32566ZM13.3503 12.5639L11.3553 10.5689C11.5215 10.2277 11.6248 9.85228 11.6248 9.45066C11.6248 8.01478 10.4356 6.82566 8.99977 6.82566C8.59814 6.82566 8.22277 6.92891 7.88239 7.09603L6.30039 5.51403C7.16885 5.21602 8.08164 5.06778 8.99977 5.07566C13.6819 5.07566 15.4958 8.44091 15.935 9.45066C15.6708 10.0562 14.9148 11.4999 13.3503 12.5639Z" fill="#1D1D1F"/>
									</svg>
									<svg x-cloak x-show="show" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.5 7.875C9.80522 7.87959 9.14021 8.15762 8.64891 8.64891C8.15762 9.14021 7.87959 9.80522 7.875 10.5C7.875 11.9367 9.06325 13.125 10.5 13.125C11.9359 13.125 13.125 11.9367 13.125 10.5C13.125 9.06412 11.9359 7.875 10.5 7.875Z" fill="#1D1D1F"/>
										<path d="M10.5004 4.375C3.8215 4.375 1.81425 10.1649 1.79588 10.2235L1.70312 10.5L1.795 10.7765C1.81425 10.8351 3.8215 16.625 10.5004 16.625C17.1792 16.625 19.1865 10.8351 19.2049 10.7765L19.2976 10.5L19.2057 10.2235C19.1865 10.1649 17.1792 4.375 10.5004 4.375ZM10.5004 14.875C5.81825 14.875 4.00437 11.5097 3.56512 10.5C4.00612 9.48675 5.82087 6.125 10.5004 6.125C15.1825 6.125 16.9964 9.49025 17.4356 10.5C16.9946 11.5132 15.1799 14.875 10.5004 14.875Z" fill="#1D1D1F"/>`;
									</svg>
								</span>
							</p>

							<?php do_action( 'woocommerce_login_form' ); ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label class="flex h-full items-center woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
									<input class="text-base relative -top-0.5 woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"  type="checkbox" id="rememberme" value="forever" /> 
									<span class="ml-2 text-[10px]/3 uppercase">{{ __( 'Remember me', 'woocommerce' ) }}</span>
								</label>
							</p>

							<p class="woocommerce-LostPassword lost_password">
								<a class="text-xs font-normal" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">{{ __( 'Lost your password?', 'woocommerce' ) }}</a>
							</p>
						
							<?php do_action( 'woocommerce_login_form_end' ); ?>
						</div>

						<p class="form-row mb-0">
							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<button type="submit" class="w-full btn-md btn-solid-primary woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">{{ __( 'Log in', 'woocommerce' ) }}</button>
						</p>

					</form>

					<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

						</div>

						<div x-cloak x-show="tabOpened == 2" class="bg-white xl:!block">

							<form method="post" class="woocommerce-form woocommerce-form-register register w-96 h-full flex flex-col justify-between text-black py-8 px-4 md:px-6" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

								<div>
									<div class="flex gap-3">
										<h2 x-on:click="tabOpened = 1" :class="tabOpened == 1 ? 'underline' : 'text-gray-400'" class="cursor-pointer xl:cursor-default xl:!text-body xl:!no-underline text-xs text-body font-bold uppercase xl:hidden">{{ __( 'Login', 'woocommerce' ) }}</h2>
										<h2 x-on:click="tabOpened = 2" :class="tabOpened == 2 ? 'underline' : 'text-gray-400'" class="cursor-pointer xl:cursor-default xl:!text-body xl:!no-underline text-xs text-body font-bold uppercase ">{{ __( 'Register', 'woocommerce' ) }}</h2>
									</div>
									<?php do_action( 'woocommerce_register_form_start' ); ?>

									<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

										<p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
											<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
											<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
										</p>

									<?php endif; ?>

									<p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
										<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="user@mail.com" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
									</p>

									<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

										<p x-data="{show:false}" class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide relative">
											<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
											<input :type="show ? 'text' : 'password'" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password"/>

											<span class="absolute right-0 bottom-0 transfrom -translate-y-[50%] cursor-pointer"  x-on:click="show = !show" >
												<svg x-show="!show" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M8.99977 15.5757C9.82752 15.5757 10.5835 15.4855 11.273 15.3298L9.73564 13.7924C9.49677 13.8108 9.25439 13.8257 8.99977 13.8257C4.31764 13.8257 2.50377 10.4604 2.06452 9.45066C2.39435 8.71461 2.83981 8.03608 3.38402 7.44078L2.16077 6.21753C0.815018 7.67616 0.304893 9.14528 0.295268 9.17416C0.234911 9.35385 0.234911 9.54834 0.295268 9.72803C0.313643 9.78578 2.32089 15.5757 8.99977 15.5757ZM8.99977 3.32566C7.39239 3.32566 6.07202 3.67216 4.97127 4.18403L1.74339 0.957031L0.506143 2.19428L16.2561 17.9443L17.4934 16.707L14.5893 13.8029C16.8765 12.0958 17.6929 9.76478 17.7051 9.72803C17.7655 9.54834 17.7655 9.35385 17.7051 9.17416C17.6859 9.11553 15.6786 3.32566 8.99977 3.32566ZM13.3503 12.5639L11.3553 10.5689C11.5215 10.2277 11.6248 9.85228 11.6248 9.45066C11.6248 8.01478 10.4356 6.82566 8.99977 6.82566C8.59814 6.82566 8.22277 6.92891 7.88239 7.09603L6.30039 5.51403C7.16885 5.21602 8.08164 5.06778 8.99977 5.07566C13.6819 5.07566 15.4958 8.44091 15.935 9.45066C15.6708 10.0562 14.9148 11.4999 13.3503 12.5639Z" fill="#1D1D1F"/>
												</svg>
												<svg x-cloak x-show="show" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M10.5 7.875C9.80522 7.87959 9.14021 8.15762 8.64891 8.64891C8.15762 9.14021 7.87959 9.80522 7.875 10.5C7.875 11.9367 9.06325 13.125 10.5 13.125C11.9359 13.125 13.125 11.9367 13.125 10.5C13.125 9.06412 11.9359 7.875 10.5 7.875Z" fill="#1D1D1F"/>
													<path d="M10.5004 4.375C3.8215 4.375 1.81425 10.1649 1.79588 10.2235L1.70312 10.5L1.795 10.7765C1.81425 10.8351 3.8215 16.625 10.5004 16.625C17.1792 16.625 19.1865 10.8351 19.2049 10.7765L19.2976 10.5L19.2057 10.2235C19.1865 10.1649 17.1792 4.375 10.5004 4.375ZM10.5004 14.875C5.81825 14.875 4.00437 11.5097 3.56512 10.5C4.00612 9.48675 5.82087 6.125 10.5004 6.125C15.1825 6.125 16.9964 9.49025 17.4356 10.5C16.9946 11.5132 15.1799 14.875 10.5004 14.875Z" fill="#1D1D1F"/>`;
												</svg>
											</span>
										</p>


									<?php else : ?>

										<p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

									<?php endif; ?>

									<?php do_action( 'woocommerce_register_form' ); ?>

									<?php do_action( 'woocommerce_register_form_end' ); ?>
								</div>

								<p class="woocommerce-form-row form-row mb-0">
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
									<button type="submit" class="w-full btn-md btn-solid-primary woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">{{ __( 'Register', 'woocommerce' ) }}</button>
								</p>

							</form>

						</div>

					</div>
					<?php endif; ?>

					<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
				</div>
			</div>
		</div>
		<div class="hidden lg:flex xl:hidden 2xl:flex overflow-hidden aspect-square">
			<img src="{{ wp_get_attachment_image_src(get_post_thumbnail_id(get_queried_object_id()), 'full')[0] ?? wc_placeholder_img_src( 'full' ) }}" alt="my account" class="object-cover h-full w-full">
		</div>
	</div>
