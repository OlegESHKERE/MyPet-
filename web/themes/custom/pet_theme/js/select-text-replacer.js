// Replace "- Any -" text in select elements
(function() {
  'use strict';
  
  function replaceSelectText() {
    // Find the specific city select element
    const citySelect = document.querySelector('select[data-drupal-selector="edit-field-city-select-value"]');
    
    if (citySelect) {
      // Find the "All" option and change its text
      const anyOption = citySelect.querySelector('option[value="All"]');
      if (anyOption) {
        anyOption.textContent = 'Choose City'; // Change this to whatever text you want
      }
      
      // Optional: Add placeholder functionality
      updatePlaceholder(citySelect);
      
      // Listen for changes to update placeholder
      citySelect.addEventListener('change', function() {
        updatePlaceholder(this);
      });
    }
  }
  
  function updatePlaceholder(select) {
    if (select.value === 'All') {
      select.setAttribute('data-placeholder', 'true');
    } else {
      select.removeAttribute('data-placeholder');
    }
  }
  
  // Run when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', replaceSelectText);
  } else {
    replaceSelectText();
  }
  
  // Also run after AJAX updates (for Drupal)
  document.addEventListener('drupalViewsAjaxView', replaceSelectText);
})();
