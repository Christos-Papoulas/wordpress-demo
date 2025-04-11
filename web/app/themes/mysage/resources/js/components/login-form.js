import {postData} from "@scripts/utilities/helper.js";

export default () => ({
    dropdownOpen: false,
    lang: null,
    errors : [],
    mobiscrollInstance: null,
    form: null,
    usernameInput: null,
    passwordInput: null,
    loggedInUser : null,
    message : null,
    init() {
        this.setup()
        this.addEventListeners()
    },
    addEventListeners() {
        document.addEventListener('closeAllModals', (event) => {
            this.dropdownOpen = false
        });
        document.addEventListener('openLoginPopUp', (event) => {
            this.openLoginPopUp(event.detail.message)
        });
    },
    setup() {
        // to solve nested components issues
        this.form = this.$refs.form
        this.usernameInput = this.$refs.username
        this.passwordInput = this.$refs.userpass

        const instance = mobiscroll.popup(this.form);
        instance.setOptions({
            display: 'center',
            buttons: [
                'cancel',
                {
                    text: 'Log in',
                    cssClass: 'my-btn', 
                    handler: (event) => {
                        this.loginUser()
                    }
                }
            ]
        });

        this.mobiscrollInstance = instance
        this.form.classList.remove('hidden')

        this.lang = this.form.dataset.lang
    },
    openLoginPopUp(message = 'Login'){
        this.message = message
        this.$dispatch('closeAllModals', {})
        this.dropdownOpen = false
        this.mobiscrollInstance.open()
    },
    loginUser() {
        return new Promise((resolve, reject) => {

            let refresh = this.form.dataset.refresh
            if( refresh == ' 1 ' || refresh == '1'){ refresh = true }else{ refresh = false}

            let credentials = {
                'user_login': this.usernameInput.value,
                'user_password': this.passwordInput.value,
                'remember': false
            }

            let requestData = {
                action: "login_user",
                lang: this.lang,
                nonce: ajax_callback_settings.ajax_nonce,
                credentials: JSON.stringify(credentials),
                secure_cookie: false
            }
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                // console.log(response)
                if (response.success) {
                    if(refresh){ location.reload() }
                    this.errors = []
                    this.loggedInUser = response.data.user
                    this.mobiscrollInstance.close()
                } else {
                    this.loggedInUser = null
                    this.errors = response.data.wp_error.errors
                }
                resolve()
            }).catch(error => {     
                console.log(error)
            });
        });
    },
})
