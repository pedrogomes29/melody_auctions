const field = document.querySelector('[name="username"]');

field.addEventListener('keypress', function ( event ) {  
   const key = event.code;
    if (key === "Space") {
      event.preventDefault();
    }
});