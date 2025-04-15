import {postData} from "@scripts/utilities/helper.js";

export default () => ({
    init() {
    },
    add(button){
        // TODO: refactor without dataset. Pass the post_id as a parameter
        let post_id = button.dataset.pid    

        let requestData = {
            action: "add_to_compare_list",
            nonce: ajax_callback_settings.ajax_nonce,
            post_id,
        }
  
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            if (response.success) {
                button.classList.add('in-my-compare-list')
                this.$dispatch('closeAllModals', {})
                this.$dispatch('openCompareListNotification', { action: 'insert' })
            }
        })

    }
})
