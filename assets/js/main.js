// assets/js/main.js
$(document).ready(function() {
    
    // Admission Form Submission
    $('#admissionForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#admissionModal').modal('hide');
                    $('#admissionForm')[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Contact Form Submission
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#contactForm')[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Smooth Scroll for Anchor Links
    $('a[href*="#"]').on('click', function(e) {
        if(this.hash !== '') {
            e.preventDefault();
            const hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 100
            }, 800);
        }
    });
    
    // Counter Animation
    function animateCounter() {
        $('.counter').each(function() {
            const $this = $(this);
            const target = parseInt($this.text());
            
            if(!isNaN(target) && !$this.hasClass('animated')) {
                $this.addClass('animated');
                $({count: 0}).animate({count: target}, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.count));
                    },
                    complete: function() {
                        $this.text(target);
                    }
                });
            }
        });
    }
    
    // Trigger counter animation when in viewport
    $(window).on('scroll', function() {
        $('.counter').each(function() {
            const elementTop = $(this).offset().top;
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if(elementTop < viewportBottom - 100) {
                animateCounter();
            }
        });
    });
    
    // Navbar Background Change on Scroll
    $(window).on('scroll', function() {
        if($(this).scrollTop() > 100) {
            $('.navbar').css('padding', '10px 0');
        } else {
            $('.navbar').css('padding', '15px 0');
        }
    });
    
    // Gallery Filter
    $('.gallery-filter').on('click', 'button', function() {
        const filterValue = $(this).data('filter');
        
        $('.gallery-filter button').removeClass('active');
        $(this).addClass('active');
        
        if(filterValue === 'all') {
            $('.gallery-item').show();
        } else {
            $('.gallery-item').hide();
            $('.gallery-item[data-category="' + filterValue + '"]').show();
        }
    });
    
    // Back to Top Button
    const backToTop = $('<button>', {
        class: 'back-to-top',
        html: '<i class="fas fa-arrow-up"></i>',
        css: {
            position: 'fixed',
            bottom: '100px',
            right: '30px',
            background: 'var(--maroon)',
            color: 'var(--gold)',
            width: '50px',
            height: '50px',
            borderRadius: '50%',
            border: 'none',
            cursor: 'pointer',
            display: 'none',
            zIndex: '99',
            boxShadow: '0 2px 10px rgba(0,0,0,0.2)'
        }
    });
    
    $('body').append(backToTop);
    
    $(window).on('scroll', function() {
        if($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });
    
    $('.back-to-top').on('click', function() {
        $('html, body').animate({scrollTop: 0}, 600);
    });
    
    // Tooltip Initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popover Initialization
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Lazy Loading Images
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imgObserver.observe(img);
        });
    }
});