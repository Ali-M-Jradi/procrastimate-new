// main-ui.js
// 1. Smooth scrolling for anchor links
$(document).on('click', 'a[href^="#"]', function(event) {
    var target = $(this.getAttribute('href'));
    if( target.length ) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top
        }, 800);
    }
});

// 2. Fade in effect for main content sections
$(document).ready(function() {
    $('.fade-section').hide().fadeIn(800);
});

// 3. Confirmation dialog for all delete forms
$(document).on('submit', 'form.delete-form', function(e) {
    if(!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
    }
});

// 4. Highlight active navigation link
$(function() {
    var path = window.location.pathname;
    $('.nav-link').each(function() {
        if (this.href.includes(path)) {
            $(this).addClass('active');
        }
    });
});

// 5. Show/hide password toggle
$(document).on('click', '.toggle-password', function() {
    var input = $(this).siblings('input[type="password"]');
    if (input.length) {
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        $(this).toggleClass('showing');
    }
});

// 6. Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

// 7. Enable tooltips (Bootstrap or custom)
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// 8. Enable copy-to-clipboard for elements with .copy-btn
$(document).on('click', '.copy-btn', function() {
    var text = $(this).data('copy') || $(this).prev('input,textarea').val();
    if (text) {
        navigator.clipboard.writeText(text);
        $(this).text('Copied!');
        var btn = $(this);
        setTimeout(function() { btn.text('Copy'); }, 1500);
    }
});
