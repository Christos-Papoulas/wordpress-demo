// Show share buttons
if (navigator.share){
    document.querySelectorAll('.navigator-share').forEach(function (element) {
      element.classList.remove('hidden')
    })
}else{
    document.querySelectorAll('.fallback-share').forEach(function (element) {
      element.classList.remove('hidden')
    })
}
