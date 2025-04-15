const targetElement = document.querySelector('#ts_woo_main_search_results > .cmtsfwc-Result');

if (targetElement) {
    // Create a MutationObserver instance with a callback function
    const observer = new MutationObserver((mutationsList, observer) => {
        // Check each mutation
        for (let mutation of mutationsList) {
        // Check if the mutation involves added nodes (new content)
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
            document.getElementById('ts_woo_main_search_loader').classList.add('hidden');
            document.getElementById('ts_woo_secondary_search_container').classList.remove('hidden');

            // Disconnect the observer to stop watching further changes
            observer.disconnect();
            break;
        }
        }
    });

    // Observer configuration: watch for child nodes being added or removed
    const config = {
        childList: true, // Watch for changes to the list of children
        subtree: true // Watch the target node and its descendants
    };

    // Start observing the target element
    observer.observe(targetElement, config);
}
