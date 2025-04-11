/**
 * Custom attribute input component
 */
export default ( { inputName, options } ) => ({
    inputName: inputName,
    options: options,
    selected: '',
    selectedLabel: '',
    disableSelection: false,
    init() {
        this.syncSelectionFormNativeInput()
        this.$watch('attributeInputs', () => {
            this.syncSelectionFormNativeInput()
        })
        this.calculateTermsAvailabilty()

        jQuery(".variations_form").on("woocommerce_variation_select_change", () => {
            this.calculateTermsAvailabilty()
        });
    },
    /**
     * setTimeout is used to wait for the native select to be updated
     * before checking the availability of the terms
     * That's why we use disableSelection to prevent the user from selecting
     * a term before the availability is calculated
     */
    calculateTermsAvailabilty(){
        this.disableSelection = true
        setTimeout(() => {
            const nativeSelect = document.querySelector("select[name='" + this.inputName + "']")
            for (let key in this.options) {
                if (nativeSelect && nativeSelect.querySelector("option[value='" + key + "']")) {
                    this.options[key].available = true
                }else{
                    this.options[key].available = false
                }
            }
            this.disableSelection = false
        }, 500)
    },
    syncSelectionFormNativeInput(){
        if(this.attributeInputs.hasOwnProperty(this.inputName)) {
            this.selected = this.attributeInputs[this.inputName]
            this.selectedLabel = this.selected === '' ? 'Make a selection' : this.options[this.selected].termName
        }
    },
    select(termSlug){
        if(this.disableSelection) {
            return
        }
        if(this.selected === termSlug) {
            return
        }

        // TODO: create a new function to handle the case when the term is not available
        // maybe let user to subscribe to a notification when the term is available
        if(!this.options[termSlug].available){
            console.log('not available')
            return
        }

        this.selected = termSlug
        this.selectedLabel = this.options[this.selected].termName
        this.attributeInputs[this.inputName] = this.selected

        const nativeSelect = document.querySelector("select[name='" + this.inputName + "']")
        nativeSelect.value = this.selected
        nativeSelect.dispatchEvent(new Event('change', { bubbles: true }))
    }
   
})
