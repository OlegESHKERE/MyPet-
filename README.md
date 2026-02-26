# My Pet Web Platform

This is a Drupal-based web platform for pet owners, providing comprehensive tools for managing pet health, diet, and community interaction.

## Features

- **Pet Profiles**: Create and manage detailed profiles for pets.
- **Health Diary**: Track daily health activities, medications, and notes.
- **Diet Planner**: Personalized diet plans with reminder notifications.
- **Pet Calculator**: Recommendations for diet and health diagnosis.
- **Veterinary Support**: 24/7 support form for vet consultations.
- **Social Interaction**: Community forum for sharing experiences.
- **Ratings & Reviews**: Rate and review pet feeds and vet clinics.
- **Multistep Registration**: User-friendly registration process.
- **REST API**: Export pet data in JSON/CSV formats.
- **External Integrations**: Connect with external APIs for feed ratings.

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Enable modules: `drush en pet_profiles health_diary diet_planner pet_calculator veterinary_support social_interaction ratings_reviews user_multistep_registration pet_user_profile pet_api -y`
4. Configure permissions and create content.

## Usage

- Access calculator at `/pet-calculator`
- Vet support at `/veterinary-support`
- Register at `/user/register/multistep`

## API Endpoints

- GET `/api/pets`: Retrieve pet data.

## Security

Data is stored securely with Drupal's encryption. User authentication required for sensitive operations.

## Contributing

Follow Drupal contribution guidelines.
