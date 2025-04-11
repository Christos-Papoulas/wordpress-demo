import { Loader } from "@googlemaps/js-api-loader"

export default ( { addressType  } ) => ({
    loading: false,
    addressType : addressType || null,
    init(){
        this.createAutocompleteInput()
    },
    createAutocompleteInput(){

        if(import.meta.env.VITE_GOOGLE_MAPS_API_KEY === undefined || import.meta.env.VITE_GOOGLE_MAPS_API_KEY === ''){ return }
        if(this.addressType === undefined || this.addressType === null){ return }

        let input
        let zipcodeInput
        let cityInput

        if(this.addressType == 'billing'){
            input = document.getElementById("billing_address_1"); 
            zipcodeInput = document.getElementById('billing_postcode')
            cityInput = document.getElementById('billing_city')
        }else if(this.addressType == 'shipping' ){
            input = document.getElementById("shipping_address_1");
            zipcodeInput = document.getElementById('shipping_postcode')
            cityInput = document.getElementById('shipping_city')
        }else{
            return
        }

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
          autocomplete.addListener('place_changed', () => {
              const place = autocomplete.getPlace(); //this callback is inherent you will see it if you logged autocomplete
              // console.log('place', place);
              for (let i = 0; i < place.address_components.length; i++) {
                if(place.address_components[i].types.includes('postal_code')){
                    zipcodeInput.value = place.address_components[i].long_name
                }
                if(place.address_components[i].types.includes('locality')){
                    cityInput.value = place.address_components[i].long_name
                }
              }
          });
      })
    }
})
