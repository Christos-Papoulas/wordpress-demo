import {postData} from "@scripts/utilities/helper.js";

export default () => ({
    loading:false,
    open:false,
    userEmail:null,
    jobdone:false,
    error: false,
    init() {
        document.addEventListener('closeAllModals', (event) => {
            this.open = false
            document.getElementById('single-product-sticky-container').classList.remove('z-[1010]');
            this.jobdone = false
            this.error = false
            this.loading = false
        })
    },
    openModal(){
        document.getElementById('single-product-sticky-container').classList.add('z-[1010]');
        document.body.classList.add('body-no-scroll');
        document.getElementById('backdrop').classList.remove('hidden');
        this.open = true
    },
    closeModal(){
        document.body.classList.remove('body-no-scroll')
        document.getElementById('backdrop').classList.add('hidden')
        this.open = false
        document.getElementById('single-product-sticky-container').classList.remove('z-[1010]');
        this.jobdone = false
        this.error = false
        this.loading = false
    },
    addRecord(productID){
        let validated
        this.loading = true
        //console.log(this.userEmail)
        validated = this.validateEmail(this.userEmail)
        if(validated){
            this.error = false

            let requestData = {
                action: "add_record_to_back_in_stock_table",
                nonce: ajax_callback_settings.ajax_nonce,
                product_id: productID,
                user_email: this.userEmail,
            }
      
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                //console.log(response)
                if(!response.success){
                    // this will fire if duplicate entry. Duplicate entries are handled by mysql
                }
                this.jobdone = true
                this.loading = false
            })

        }else{
            this.jobdone = false
            this.loading = false
            this.error = 'Αυτό δεν φαίνεται να είναι έγκυρο email.'
        }
    },
    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
})
