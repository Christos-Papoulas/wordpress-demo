import { postData } from "@scripts/utilities/helper.js";
import { Loader } from "@googlemaps/js-api-loader"

export default ( { items, total } ) => ({
    loading: true,
    shippingMethodsLoading: false,
    showReviewOrderDropdown: false,
    showAllProductsInReview: false,
    couponInputShow: false,
    coupon_error: false,
    coupon_errorMessage: '',
    orderTotalAmountHtml: {
        loading: true,
        value: ''
    },
    coupons: [],
    invoice: {
        loading: false,
        enabled: false
    },
    items: items,
    total: total,
    gtmEventsFired: {
        beginCheckout: false,
        addPaymentInfo: false,
        addShippingInfo: false
    },
    async init() {
        await this.checkIfCartsAreSynced()

        // prevent form submission on enter, this is crusial.
        // for example when pressing enter after chaning the billing zip code,
        // we dont want to submit the form.
        // keypress is deprecated, use keydown
        jQuery('form.checkout').on('keydown',function(e) {
            if (e.which == 13) {
               return false;
            }
        });

        this.createAutocompleteInput()

        jQuery(document.body).on('update_checkout', () => {
            this.shippingMethodsLoading = true
            this.orderTotalAmountHtml.loading = true;
        })
        jQuery(document.body).on('updated_checkout', () => {
            this.refreshShippingMethods()
            this.renderOrderTotalAtDropdown()
        })

        jQuery('form.checkout').on('change', 'input[name="payment_method"]', () => {
			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: true } );
		});
		jQuery('form.checkout').on('change', 'input[name="billing_postcode"]', () => {
			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: true } );
		});
		jQuery('form.checkout').on('change', 'select[name="billing_state"]', () => {
			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: true } );
		});
		jQuery('form.checkout').on('change', 'input[name="shipping_postcode"]', () => {
			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: true } );
		});
		jQuery('form.checkout').on('change', 'select[name="shipping_state"]', () => {
			jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: true } );
		});

        jQuery(document).on('change', 'input[name=payment_method]', () => {
            this.$dispatch('addPaymentInfo', { total: this.total })
            this.gtmEventsFired.addPaymentInfo = true
        });

        jQuery(document).on('change', 'input[name^=shipping_method]', () => {
            this.$dispatch('addShippingInfo', { total: this.total })
            this.gtmEventsFired.addShippingInfo = true
        });

        // If user doesnt change the payment or shipping method, we fire these events before the checkout is submitted
		jQuery('form.checkout').on('checkout_place_order', () => {
            if(!this.gtmEventsFired.addPaymentInfo){
                this.$dispatch('addPaymentInfo', { total: this.total })
                this.gtmEventsFired.addPaymentInfo = true
            }
            if(!this.gtmEventsFired.addShippingInfo){
                this.$dispatch('addShippingInfo', { total: this.total })
                this.gtmEventsFired.addShippingInfo = true
            }
        })

        document.getElementById('ht_invoice_plugin_vat').addEventListener('keyup', (event) => {
            if (event.defaultPrevented) {
                return // Should do nothing if the default action has been cancelled
            }

            let handled = false
            if (event.key !== undefined && event.key == 13) {
                // Handle the event with KeyboardEvent.key
                handled = true
            } else if (event.keyCode !== undefined && event.keyCode == 13) {
                // Handle the event with KeyboardEvent.keyCode
                handled = true
            }

            if (handled) {
                // Suppress "double action" if event handled
                event.preventDefault()
                this.validateVatNumber()
            }
        })

        document.addEventListener('closeAllModals', (event) => {
            this.closeReviewOrderDropdown()
        });

        this.$dispatch('beginCheckout', { items: this.items, total: this.total })
        this.gtmEventsFired.beginCheckout = true
    },
    async checkIfCartsAreSynced(){
        let ht_cart = localStorage.getItem('ht_cart')
        if(ht_cart === undefined || ht_cart === null){
            ht_cart = {
                isSynced : false,
                items : {}
            }
        }else{
            ht_cart = JSON.parse(ht_cart)
        }
        if(!ht_cart.isSynced){
            location.href = global_app_data.cart_url
            return
        }
    },
    applyCoupon(couponCode) {
        return new Promise((resolve, reject) => {

            if (!couponCode) {
                resolve()
                return;
            }

            let requestData = {
                action: "apply_coupon",
                nonce: ajax_callback_settings.ajax_nonce,
                coupon_code: couponCode
            }

            postData(ajax_callback_settings.ajax_url, requestData).then(data => {
                if (data.success) {
                    this.couponInputShow = false;
                    this.coupon_error = false;
                    this.coupon_errorMessage = '';
                } else {
                    this.coupon_error = true;
                    this.coupon_errorMessage = data.data.message;
                }

                // Trigger events
                jQuery(document.body).trigger('update_checkout', { update_shipping_method: false });
                resolve()

            })

        })
    },
    renderOrderTotalAtDropdown() {
        // Get the total cart price
        this.orderTotalAmountHtml.value = jQuery('.order-total .woocommerce-Price-amount').html()
        this.orderTotalAmountHtml.loading = false;
    },
    refreshShippingMethods() {

        let requestData = {
            action: "get_shipping_methods_for_checkout",
            nonce: ajax_callback_settings.ajax_nonce,
        }

        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            // return
            if (response.success) {
                document.getElementById('ht-checkout-shipping-methods').innerHTML = response.data.html
            }
            this.shippingMethodsLoading = false
        })

    },
    toggleInvoice() {
        this.invoice.enabled = !this.invoice.enabled
        const inputs = document.querySelectorAll('.ht_invoice_plugin_input')
        if (this.invoice.enabled) {
            document.getElementById('ht_invoice_enabled').checked = true
            inputs.forEach(input => {
                input.classList.remove('hidden')
            })
        } else {
            document.getElementById('ht_invoice_enabled').checked = false
            inputs.forEach(input => {
                input.classList.add('hidden')
            })
            document.getElementById('ht_invoice_enabled').checked = false
            document.getElementById('ht_invoice_plugin_vat').value = ''
            document.getElementById('ht_invoice_plugin_tax_office').value = ''
            document.getElementById('ht_invoice_plugin_company_name').value = ''
            document.getElementById('ht_invoice_plugin_company_activity').value = ''
        }
    },
    async validateVatNumber() {
        this.invoice.loading = true
        const vat = document.getElementById('ht_invoice_plugin_vat').value

        let requestData = {
            action: "get_company_info_from_vat",
            nonce: ajax_callback_settings.ajax_nonce,
            vat: vat
        }

        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            if (response.success) {
                if(response.data.tax_office !== undefined){ document.getElementById('ht_invoice_plugin_tax_office').value = response.data.tax_office }
                if(response.data.name !== undefined){ document.getElementById('ht_invoice_plugin_company_name').value = response.data.name }
                if(response.data.activity !== undefined){ document.getElementById('ht_invoice_plugin_company_activity').value = response.data.activity }
                if(response.data.postal_zip_code !== undefined){ document.getElementById('billing_postcode').value = response.data.postal_zip_code }
                let address = response.data.postal_address
                if(response.data.postal_address_no !== undefined && response.data.postal_address_no !== null && response.data.postal_address_no != ''){
                  address = address + ' ' + response.data.postal_address_no
                }
                if(address !== undefined){ document.getElementById('billing_address_1').value = address }
                if(response.data.postal_area_description !== undefined){ document.getElementById('billing_city').value = response.data.postal_area_description }
            } else {
                document.getElementById('ht_invoice_plugin_tax_office').value = ''
                document.getElementById('ht_invoice_plugin_company_name').value = ''
                document.getElementById('ht_invoice_plugin_company_activity').value = ''
                document.getElementById('billing_postcode').value = ''
                document.getElementById('billing_address_1').value = ''
                document.getElementById('billing_city').value = ''
                
                mobiscroll.alert({
                    title: response.data.title,
                    message: response.data.message
                });
            }
            this.invoice.loading = false
        })
    },
    toggleReviewOrderDropdown(){
        if(!this.showReviewOrderDropdown){
            document.dispatchEvent(new CustomEvent('closeAllModals', {detail: {}}))
            this.showReviewOrderDropdown = true
            document.body.classList.add('body-no-scroll')
            document.getElementById('backdrop').classList.remove('hidden')
        }else{
            this.closeReviewOrderDropdown()
        }
    },
    closeReviewOrderDropdown(){
        this.showReviewOrderDropdown = false
        document.body.classList.remove('body-no-scroll')
        document.getElementById('backdrop').classList.add('hidden')
    },
    createAutocompleteInput() {

        if(import.meta.env.VITE_GOOGLE_MAPS_API_KEY === undefined || import.meta.env.VITE_GOOGLE_MAPS_API_KEY === ''){ return }
        const loader = new Loader({
            apiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
            version: "weekly",
        })

        loader.load().then(async () => {

            const Places = await loader.importLibrary('places')

            // the center, defaultbounds are not necessary but are best practices to limit/focus search results
            const center = { lat: 34.082298, lng: -82.284777 };
            // Create a bounding box with sides ~10km away from the center point
            const defaultBounds = {
                north: center.lat + 0.1,
                south: center.lat - 0.1,
                east: center.lng + 0.1,
                west: center.lng - 0.1,
            };

            // For billing address
            const input = document.getElementById("billing_address_1"); //binds to our input element
            const options = {
                bounds: defaultBounds, //optional
                //types: ["establishment"], //optional
                componentRestrictions: { country: "gr" }, //limiter for the places api search
                fields: ["address_components", "geometry", "icon", "name"], //allows the api to accept these inputs and return similar ones
                strictBounds: false, //optional
            };
            // per the Google docs create the new instance of the import above. I named it Places.
            const autocomplete = new Places.Autocomplete(input, options);

            //console.log('autocomplete', autocomplete); //optional log but will show you the available methods and properties of the new instance of Places.

            //add the place_changed listener to display results when inputs change
            // autocomplete.addListener('place_changed', () => {
            //     const place = autocomplete.getPlace(); //this callback is inherent you will see it if you logged autocomplete
            //     console.log('place', place);
            // });

            // For shipping address
            const input2 = document.getElementById("shipping_address_1"); //binds to our input element
            const autocomplete2 = new Places.Autocomplete(input2, options);
        })
    }
})
