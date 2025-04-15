import md5 from 'md5';

export function outside(element) {
    const outsideClickListener = event => {
      // https://caniuse.com/?search=composedPath
      const insideElement = event.composedPath().includes(element)
  
      if (!insideElement) {
        element.style.display = 'none';
        removeClickListener();
      }
    }
  
    const removeClickListener = () => {
      document.removeEventListener('click', outsideClickListener);
    }
  
    document.addEventListener('click', outsideClickListener);
  }
  
  export function keydown(element) {
    const keydownListener = event => {
      if (event.key === 'Escape') {
        element.style.display = 'none';
        removeClickListener();
      }
    }
  
    const removeClickListener = () => {
      document.removeEventListener('keydown', keydownListener);
    }
  
    document.addEventListener('keydown', keydownListener);
  }
  
  export const debounce = (func, wait) => {
    let timeout;
  
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
  
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  };
  
  export function throttle(func, limit) {
    let timeout;
    return function () {
      const context = this;
      const args = arguments;
      if (!timeout) {
        timeout = setTimeout(function () {
          func.apply(context, args);
          timeout = null;
        }, limit);
      }
    };
  }
  
  export function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  
  export async function postData(url = "", data = {}) {
    const response = await fetch(url, {
      method: "POST", // *GET, POST, PUT, DELETE, etc.
      mode: "cors", // no-cors, *cors, same-origin
      cache: "default", // *default, no-cache, reload, force-cache, only-if-cached
      credentials: "same-origin", // include, *same-origin, omit
      headers: {
        // "Content-Type": "application/json",
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      redirect: "follow", // manual, *follow, error
      referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
      body: new URLSearchParams(data).toString(),
    });
    return response.json();
  }
  
  export function handleAllPromises(results) {
    let info = {
      status: 'success'
    }

    for (const promise of results) {
        if(promise.status != 'fulfilled'){
          info.status = 'failed'
          break
        }
    }

    return [
      results,
      info
    ]
  }
  
  export function removeGreekAccents(str) {
    // Update characters as necessary
    /*  var from = "ãàáäâèéëêìíïîõòóöôùúüûñç";
      var to = "aaaaaeeeeiiiiooooouuuunc"; */
    var from = "ήέύίόάώ";
    var to = "ηευιοαω";
    var l = from.length;
    for (var i = 0; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'gi'), to.charAt(i));
    }
    return str;
  }
  
  /**
   * Generate a unique ID for the cart item being added. 
   * This function copies the generate_cart_id() of woocommerce.
   * @see 'wp-content/plugins/woocommerce/includes/class-wc-cart.php'
   * 
   * @param {int} productId 
   * @param {int} variationId 
   * @param {*} variation 
   * @param {*} cartItemData 
   * @returns 
   */
  export function generateCartId(productId, variationId = 0, variation = {}, cartItemData = {}) {
    let idParts = [productId];

    if (variationId && variationId !== 0) {
        idParts.push(variationId);
    }

    if (typeof variation === 'object' && Object.keys(variation).length > 0) {
        let variationKey = '';
        for (const [key, value] of Object.entries(variation)) {
            variationKey += key.trim() + value.toString().trim();
        }
        idParts.push(variationKey);
    }

    if (typeof cartItemData === 'object' && Object.keys(cartItemData).length > 0) {
        let cartItemDataKey = '';
        for (const [key, value] of Object.entries(cartItemData)) {
            let finalValue = value;
            if (typeof value === 'object') {
                finalValue = new URLSearchParams(value).toString();
            }
            cartItemDataKey += key.trim() + finalValue.toString().trim();
        }
        idParts.push(cartItemDataKey);
    }

    return md5(idParts.join('_'));
}

/**
 * Gets a hash of important product data that when changed should cause cart items to be invalidated.
 * This function copies the generate_cart_id() of woocommerce.
 *  @see 'wp-content/plugins/woocommerce/includes/wc-cart-functions.php'
 * 
 * @param {string} type 
 * @param {array} variation_attr 
 * @returns 
 */
  export function generateCartDataHash(type, variation_attr = []) {
    let data = {
      'type' : type,
      'attributes' : 'variation' === type ? variation_attr : ''
    }

    return md5(JSON.stringify(data));
  }

/**
 * Return price with the proper format for thousands and decimals
 * TODO: read from woocommerce options.
 * 
 * @param {float} number 
 * @returns 
 */
  export function format_wc_price(number){
    const formattedNumberGR = new Intl.NumberFormat('el-GR', {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(number);

    return formattedNumberGR
  }
