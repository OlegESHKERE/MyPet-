// JS from attached calculator code

(function ($, Drupal) {
  Drupal.behaviors.petCalculator = {
    attach: function (context, settings) {
      // Include the JavaScript logic for the calculator
      // Breed arrays, validation functions, score calculation, PDF generation

      const breedSmall = [
        // List of small breeds from attached code
      ];

      const breed = [
        // List of breeds
      ];

      // Function to populate select
      var select = document.getElementById("breed");
      if (select) {
        // Populate breeds
      }

      // Form validation and calculation logic
      // Add event listeners, etc.

      // PDF generation using jsPDF
      // Include the generatePDF function
    }
  };
})(jQuery, Drupal);