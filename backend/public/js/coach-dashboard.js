// coach-dashboard.js
// Add smooth scrolling for anchor links
$(document).on('click', 'a[href^="#"]', function(event) {
    var target = $(this.getAttribute('href'));
    if( target.length ) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top
        }, 800);
    }
});

// Add fade in effect for dashboard sections
$(document).ready(function() {
    $('.dashboard-section').hide().fadeIn(800);
});

// Add confirmation dialog for group/task deletion
$(document).on('submit', '.delete-form', function(e) {
    if(!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
    }
});

// Highlight active navigation link
$(function() {
    var path = window.location.pathname;
    $('.nav-link').each(function() {
        if (this.href.includes(path)) {
            $(this).addClass('active');
        }
    });
});
