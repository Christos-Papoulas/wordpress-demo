// this js file can be used to create a brands template like avance.gr
// import { postData } from "@scripts/utilities/helper.js"
// import { removeGreekAccents } from "@scripts/utilities/helper.js";

// export default () => ({
//     lang: null,
//     brands: [],
//     filteredBrands: [],
//     filteredAlphabetArrays: [],
//     alphabetArrays: [],
//     activeLetter: null,
//     async init() {
//         this.lang = this.$refs.brandsContainer.dataset.lang
//         await this.getBrands()
//     },
//     async getBrands(){
//         let requestData = {
//             action: "get_all_brands",
//             nonce: ajax_callback_settings.ajax_nonce,
//             lang: this.lang,
//         }
//         await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
//             //console.log(response)
//             this.brands = Object.values(response.data.brands)
//             this.filteredBrands = this.brands
//             this.alphabetArrays = this.createAlphabetArray(this.filteredBrands)
//             this.filteredAlphabetArrays = this.alphabetArrays
//         })
//         //console.log(this)
//     },
//     createAlphabetArray(objects) {
//         let result
//         if(this.lang == 'el'){
//             result = {
//                 'α':[],'β':[],'γ':[],'δ':[],'ε':[],'ζ':[],'η':[],'θ':[],'ι':[],
//                 'κ':[], 'λ':[], 'μ':[], 'ν':[],'ξ':[],'ο':[],'π':[],'ρ':[],
//                 'σ':[], 'τ':[],'υ':[],'φ':[],'χ':[],'ψ':[],'ω':[]
//             }
//         }else{
//             result = {
//                 'a':[],'b':[],'c':[],'d':[],'e':[],'f':[],'g':[],'h':[],'i':[],
//                 'g':[],'k':[], 'l':[], 'm':[], 'n':[],'o':[],'p':[],'q':[],'r':[],
//                 's':[], 't':[],'u':[],'v':[],'x':[],'y':[],'z':[]
//             }
//         }

//         objects.forEach(obj => {
//             const firstLetter = removeGreekAccents(obj.name.charAt(0).toLowerCase())
//             if (!result[firstLetter]) {
//               result[firstLetter] = []
//             }
//             result[firstLetter].push(obj)
//         })
//         // console.log(result)
//         return result
//     },
//     clear(){
//         this.activeLetter = null
//         this.filteredBrands = this.brands
//         this.filteredAlphabetArrays = this.alphabetArrays
//         this.$refs.anchorForScroll.scrollIntoView({ behavior: 'smooth' })
//     },
//     filterByFirstLetter(letter){
//         this.activeLetter = letter
//         this.filteredBrands = this.alphabetArrays[letter]
//         this.filteredAlphabetArrays = this.createAlphabetArray(this.filteredBrands)
//         this.$refs.anchorForScroll2.scrollIntoView({ behavior: 'smooth' })
//     },
// })

