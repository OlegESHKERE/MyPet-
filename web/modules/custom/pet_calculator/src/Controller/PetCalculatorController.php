<?php

namespace Drupal\pet_calculator\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the Pet Calculator module.
 */
class PetCalculatorController extends ControllerBase {

  /**
   * Displays the pet calculator page.
   */
  public function calculator() {
    $build = [
      '#type' => 'markup',
      '#markup' => $this->getCalculatorMarkup(),
      '#attached' => [
        'library' => [
          'pet_calculator/calculator',
        ],
      ],
    ];
    return $build;
  }

  /**
   * Returns the HTML markup for the calculator.
   */
  private function getCalculatorMarkup() {
    // Full HTML from attached calculator code.
    return '
      <!-- Insert the full HTML from the attached live calc back up (1).txt here -->
      <div class="container-fluid banner-cds">
        <!-- Banner content -->
        <div class="wrap-banner">
          <div class="images-right-banner">
            <img src="/sites/default/files/2024-07/banner_image.png" alt="Banner" />
          </div>
          <div class="banner-cds-text">
            <h1>Pet Calculator</h1>
            <h2>Recommendations for your pet</h2>
            <p>Calculate the best diet and health tips.</p>
          </div>
        </div>
      </div>
      <div class="main-custom-wrap">
        <div class="container">
          <form id="submit">
            <!-- Form fields from attached code -->
            <div class="form-group">
              <label for="pet_name">Pet Name</label>
              <input type="text" id="pet_name" name="pet_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" id="age" name="age" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="breed">Breed</label>
              <select id="breed" name="breed" class="form-control" required>
                <option value="">Select Breed</option>
              </select>
            </div>
            <!-- Add more fields as per attached code -->
            <button type="submit" class="btn btn-primary">Calculate</button>
          </form>
          <div id="results"></div>
        </div>
      </div>
      <!-- Scripts from attached code -->
      <script>
        // Include JS logic for calculation
        const breedSmall = [];
        const breed = [];
        // Populate breeds
        // Form validation and calculation
      </script>
    ';
  }

}