// Neon Kactus - JavaScript
// Add any interactive functionality here

document.addEventListener('DOMContentLoaded', function() {
    console.log('Neon Kactus site loaded successfully!');
    
    // Example: Add active class to nav links on click
    const navLinks = document.querySelectorAll('.nav-links a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');
        });
    });
});
