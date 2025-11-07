// Defensive JavaScript that works in both BrowserSync and Drupal aggregation
(function () {
  'use strict';
  
  console.log('=== SIMPLE TEST: JavaScript file is loading ===');
  
  // Wait for DOM and dependencies to be ready
  function initializePetTheme() {
    console.log('=== TEST: Initializing Pet Theme JavaScript ===');
    
    // Check if we're in a Drupal environment
    if (typeof window.Drupal !== 'undefined' && typeof window.jQuery !== 'undefined') {
      console.log('SUCCESS: Drupal and jQuery are available');
      
      // Use Drupal behaviors pattern
      window.Drupal.behaviors.petThemeAutoSubmit = {
        attach: function (context, settings) {
          console.log('=== TEST: Drupal behavior attach() called ===');
          console.log('Context:', context);
          
          // Only run on /home page - Set default checkbox checked and highlighted
          if (window.location.pathname === '/home') {
            var defaultCheckbox = window.jQuery('#edit-tid-1', context);
            if (defaultCheckbox.length) {
              defaultCheckbox.prop('checked', true);
              
              // Add highlighted class to the parent container
              var checkboxContainer = defaultCheckbox.closest('.form-type-boolean');
              checkboxContainer.addClass('highlighted');
              
              console.log('=== DEFAULT CHECKBOX SET AND HIGHLIGHTED: edit-tid-1 ===');
            }
          }
          
          // Handle the specific submit button redirect (works on any domain)
          window.jQuery('#edit-submit-blog-view', context).on('click', function(e) {
            console.log('=== BLOG SUBMIT BUTTON CLICKED ===');
            e.preventDefault(); // Prevent default form submission
            e.stopPropagation(); // Stop event bubbling
            e.stopImmediatePropagation(); // Stop all other handlers
            
            console.log('=== REDIRECTING TO /blogs ===');
            // Use relative URL that works regardless of domain
            window.location.href = '/blogs';
            return false;
          });
          
          // Find checkboxes and attach click event for auto-submit
          window.jQuery('input[type="checkbox"]', context).on('click', function() {
            console.log('=== CHECKBOX CLICKED ===');
            console.log('Checkbox ID:', window.jQuery(this).attr('id'));
            console.log('Checked:', window.jQuery(this).is(':checked'));
            
            // Auto-submit the form when checkbox is clicked
            var form = window.jQuery(this).closest('form');
            if (form.length) {
              console.log('=== AUTO-SUBMITTING FORM ===');
              form.submit();
            }
          });
          
          console.log('=== TEST: Event listener attached ===');
        }
      };
    } else {
      console.log('WARNING: Drupal or jQuery not available, trying direct approach');
      
      // Fallback for non-Drupal environments
      if (typeof window.jQuery !== 'undefined') {
        window.jQuery(document).ready(function($) {
          console.log('=== FALLBACK: Using jQuery ready ===');
          $('input[type="checkbox"]').on('click', function() {
            console.log('=== CHECKBOX CLICKED (FALLBACK) ===');
            console.log('Checkbox ID:', $(this).attr('id'));
            console.log('Checked:', $(this).is(':checked'));
          });
        });
      }
    }
  }
  
  // Try to initialize immediately
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePetTheme);
  } else {
    initializePetTheme();
  }
  
})();
