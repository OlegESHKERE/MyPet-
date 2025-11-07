/**
 * @file
 * JavaScript for handling city and oblast select defaults.
 */

(function ($, Drupal) {
  'use strict';

  /**
   * Initialize city and oblast select fields with default empty values.
   */
  Drupal.behaviors.cityObastSelectDefaults = {
    attach: function (context, settings) {
      
      // Handle exposed filters specifically
      $('.views-exposed-form', context).once('exposed-filters-init').each(function() {
        var $form = $(this);
        
        // Hide apply button but keep it in DOM for functionality
        var $submitButton = $form.find('input[type="submit"][value*="Apply"], input[type="submit"][value*="apply"], .form-submit');
        $submitButton.hide();
        
        // Handle city selects in exposed filters
        $form.find('select').each(function() {
          var $select = $(this);
          var selectName = $select.attr('name') || '';
          
          // Check if this is a city field
          if (selectName.indexOf('city') !== -1 || selectName.indexOf('City') !== -1) {
            // Remove default selection of Київ
            $select.find('option[value="Київ"]').removeAttr('selected');
            
            // Set to "All" or first option
            if ($select.find('option[value="All"]').length > 0) {
              $select.val('All');
              $select.find('option[value="All"]').text('Choose city');
            } else if ($select.find('option[value=""]').length > 0) {
              $select.val('');
            }
          }
          
          // Check if this is an oblast/region field
          if (selectName.indexOf('oblast') !== -1 || selectName.indexOf('region') !== -1 || selectName.indexOf('Oblast') !== -1) {
            // Remove default selection of Київська Область
            $select.find('option[value="Київська Область"]').removeAttr('selected');
            
            // Set to "All" or first option
            if ($select.find('option[value="All"]').length > 0) {
              $select.val('All');
              $select.find('option[value="All"]').text('Any');
            } else if ($select.find('option[value=""]').length > 0) {
              $select.val('');
            }
          }
        });
        
        // Auto-submit on select change
        $form.find('select').on('change', function() {
          var $changedSelect = $(this);
          var $form = $changedSelect.closest('form');
          
          // Small delay to ensure the change is processed
          setTimeout(function() {
            // Trigger the submit button click instead of form submit
            var $submitBtn = $form.find('input[type="submit"], .form-submit').first();
            if ($submitBtn.length) {
              $submitBtn.click();
            } else {
              // Fallback to form submit
              $form.submit();
            }
          }, 100);
        });
        
        // Also handle any other form elements that might need auto-submit
        $form.find('input[type="text"], input[type="search"]').on('keyup', function(e) {
          if (e.keyCode === 13) { // Enter key
            var $form = $(this).closest('form');
            var $submitBtn = $form.find('input[type="submit"], .form-submit').first();
            if ($submitBtn.length) {
              $submitBtn.click();
            }
          }
        });
      });
      
      // Handle regular form city select fields
      $('select[name*="field_city_select"], select[name*="field_city"]', context).once('city-select-init').each(function() {
        var $select = $(this);
        
        // Add default empty option if it doesn't exist
        if ($select.find('option[value=""]').length === 0) {
          $select.prepend('<option value="">Choose city</option>');
        }
        
        // Set default selection to empty
        $select.val('');
      });
      
      // Handle oblast/region select fields
      $('select[name*="field_select_oblast"], select[name*="field_region"]', context).once('oblast-select-init').each(function() {
        var $select = $(this);
        
        // Add default empty option if it doesn't exist
        if ($select.find('option[value=""]').length === 0) {
          $select.prepend('<option value="">Any</option>');
        }
        
        // Set default selection to empty
        $select.val('');
        
        // Update the select to show the placeholder
        if ($select.val() === '' || $select.val() === null) {
          $select.find('option[value=""]').prop('selected', true);
        }
      });
      
      // Handle AJAX forms and exposed filters
      $('form', context).once('city-oblast-form-init').each(function() {
        var $form = $(this);
        
        // Reset selects when form is reset
        $form.on('reset', function() {
          setTimeout(function() {
            $form.find('select[name*="field_city_select"], select[name*="field_city"]').val('');
            $form.find('select[name*="field_select_oblast"], select[name*="field_region"]').val('');
          }, 100);
        });
      });
      
      // Handle exposed filters specifically
      $('.views-exposed-form', context).once('exposed-filter-init').each(function() {
        var $form = $(this);
        
        // Set default values for exposed filters
        $form.find('select[name*="city"], select[name*="oblast"], select[name*="region"]').each(function() {
          var $select = $(this);
          if ($select.find('option[value="All"]').length > 0) {
            $select.val('All');
          } else if ($select.find('option[value=""]').length > 0) {
            $select.val('');
          }
        });
      });
    }
  };

})(jQuery, Drupal);
