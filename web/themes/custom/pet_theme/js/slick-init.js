/**
 * Slick Slider Initialization for Pet Theme
 */
(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.petThemeSlick = {
    attach: function (context, settings) {
      
      // Basic slider initialization
      $('.slick-slider', context).once('pet-slick').each(function() {
        $(this).slick({
          dots: true,
          infinite: true,
          speed: 500,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 3000,
          arrows: true,
          fade: false,
          cssEase: 'ease-in-out'
        });
      });

      // Pet cards slider - if you want to make pet cards a slider
      $('.view-pet-card-view .view-content', context).once('pet-cards-slick').each(function() {
        $(this).slick({
          dots: true,
          infinite: true,
          speed: 500,
          slidesToShow: 3,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 4000,
          arrows: true,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });
      });

      // Hero slider - if you have a hero section
      $('.hero-slider', context).once('hero-slick').each(function() {
        $(this).slick({
          dots: true,
          infinite: true,
          speed: 800,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 5000,
          arrows: true,
          fade: true,
          cssEase: 'ease-in-out'
        });
      });

      // Gallery slider - for image galleries
      $('.gallery-slider', context).once('gallery-slick').each(function() {
        $(this).slick({
          dots: false,
          infinite: true,
          speed: 300,
          slidesToShow: 4,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });
      });

      // Testimonials slider
      $('.testimonials-slider', context).once('testimonials-slick').each(function() {
        $(this).slick({
          dots: true,
          infinite: true,
          speed: 600,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 6000,
          arrows: false,
          fade: true
        });
      });

    }
  };

})(jQuery, Drupal);
