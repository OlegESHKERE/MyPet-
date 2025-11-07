/**
 * Reviews Slider Fix
 * Forces Slick initialization when the module fails
 */
(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.reviewsSliderForce = {
    attach: function (context, settings) {
      
      // Target the reviews slider - try multiple selectors
      var $reviewsContainer = $('.view-revies-slider', context).once('reviews-slider-init');
      
      if ($reviewsContainer.length) {
        
        // Find the actual slides container
        var $slickContainer = $reviewsContainer.find('.slick').first();
        var $viewContent = $reviewsContainer.find('.view-content').first();
        
        // If we have the Drupal Slick module structure
        if ($slickContainer.length && $slickContainer.hasClass('unslick')) {
          
          console.log('Found unslick container, attempting to fix...');
          
          // Remove unslick class
          $slickContainer.removeClass('unslick');
          
          // Find individual slides
          var $slides = $slickContainer.find('.slick__slide');
          
          if ($slides.length > 1) {
            // Initialize Slick
            $slickContainer.slick({
              dots: true,
              infinite: true,
              speed: 500,
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 4000,
              arrows: true,
              fade: true,
              cssEase: 'ease-in-out',
              prevArrow: '<button type="button" class="slick-prev" aria-label="Previous">❮</button>',
              nextArrow: '<button type="button" class="slick-next" aria-label="Next">❯</button>'
            });
            
            console.log('Slick initialized on existing structure');
          }
        }
        // Fallback: if no Slick module structure, create our own
        else if ($viewContent.length && !$viewContent.hasClass('slick-initialized')) {
          
          var $reviews = $viewContent.find('.field__item');
          
          if ($reviews.length > 1) {
            // Wrap each review
            $reviews.each(function() {
              $(this).wrap('<div class="review-slide"></div>');
            });
            
            // Initialize Slick on view content
            $viewContent.slick({
              dots: true,
              infinite: true,
              speed: 500,
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 4000,
              arrows: true,
              fade: true,
              cssEase: 'ease-in-out',
              prevArrow: '<button type="button" class="slick-prev" aria-label="Previous">❮</button>',
              nextArrow: '<button type="button" class="slick-next" aria-label="Next">❯</button>'
            });
            
            console.log('Slick initialized on view content');
          }
        }
      }
    }
  };

})(jQuery, Drupal);
