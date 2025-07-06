 window.addEventListener('load', function() {
     const backgroundImage = localStorage.getItem('backgroundImage'); 
     if (backgroundImage) {
         document.body.style.backgroundImage = `url(${backgroundImage})`;
         } });