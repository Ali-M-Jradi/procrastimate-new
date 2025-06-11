// main-ui.js

// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);

// Smooth scrolling for anchor links
$(document).on('click', 'a[href^="#"]', function(event) {
    var target = $(this.getAttribute('href'));
    if (target.length) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top
        }, 800);
    }
});

// Fade in effect for dashboard sections
$(document).ready(function() {
    $('.dashboard-section').hide().fadeIn(800);
});

// Confirmation dialog for group/task deletion
$(document).on('submit', '.delete-form', function(e) {
    if (!confirm('Are you sure you want to delete this item?')) {
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

// Global Loading Spinner
$(document).on({
    ajaxStart: function() {
        $('body').append('<div class="loading-spinner">Loading...</div>');
    },
    ajaxStop: function() {
        $('.loading-spinner').remove();
    }
});

// Dynamic Page Title Update
$(function() {
    var activeLink = $('.nav-link.active').text();
    if (activeLink) {
        document.title = activeLink + ' - Procrastimate';
    }
});

// Scroll-to-Top Button
$(document).ready(function() {
    // Remove any existing scroll-to-top buttons to avoid duplicates
    $('.scroll-to-top').remove();

    // Append the button to the body with a very high z-index and !important
    $('body').append('<button class="scroll-to-top" style="display:none;position:fixed;bottom:20px;right:20px;background:#007bff;color:#fff;border:none;padding:10px;border-radius:50%;cursor:pointer;z-index:99999 !important;">↑</button>');

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    $('.scroll-to-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
    });
});

// Global Error Handling for AJAX
$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
    alert('An error occurred: ' + thrownError);
});