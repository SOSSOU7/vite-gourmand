
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.liens-nav');

    
    mobileMenu.addEventListener('click', () => {
       
        navLinks.classList.toggle('active');
        
        
        mobileMenu.classList.toggle('is-active');
    });
});