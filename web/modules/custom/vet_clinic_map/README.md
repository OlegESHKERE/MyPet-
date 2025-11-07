# Vet Clinic Map Module

## Overview
The Vet Clinic Map module provides a comprehensive solution for finding veterinary clinics based on region and city selection. It integrates with Google Maps to display clinic locations with interactive markers and detailed information.

## Features
- **Interactive Form**: Region and city selection with AJAX-powered dependent dropdowns
- **Google Maps Integration**: Interactive map with custom clinic markers
- **Responsive Design**: Works on desktop and mobile devices
- **Real-time Search**: Auto-submit functionality when selections change
- **Clinic Information**: Display of clinic details including address, phone, and email
- **Info Windows**: Click markers to view clinic details in map popup

## Installation
1. Copy the module to your `web/modules/custom/` directory
2. Enable the module through the Drupal admin interface or with Drush:
   ```bash
   drush en vet_clinic_map
   ```
3. Configure your Google Maps API key (see Configuration section)

## Configuration

### Google Maps API Key
1. Get a Google Maps API key from the [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the following APIs:
   - Maps JavaScript API
   - Places API (optional, for enhanced features)
3. Configure the API key in your settings file or through the module configuration

### Content Type Requirements
This module expects a content type (e.g., "vet_clinic") with the following fields:
- `field_select_oblast` - Region/Oblast selection
- `field_city_select` - City selection
- `field_address` - Clinic address
- `field_phone` - Phone number
- `field_email` - Email address
- `field_latitude` - Latitude coordinate
- `field_longitude` - Longitude coordinate

## Usage

### Accessing the Clinic Finder
Visit `/vet-clinics` to access the clinic finder page.

### API Endpoints
- `GET /vet-clinics/ajax/{region}/{city}` - Returns JSON data of clinics for the specified location

## Customization

### Adding More Regions/Cities
Edit the `VetClinicFinderForm::getRegionOptions()` and `VetClinicFinderForm::getCityOptions()` methods to add more location options.

### Styling
Customize the appearance by modifying `css/vet-clinic-map.css` or overriding styles in your theme.

### Map Markers
Customize the map marker icons by modifying the JavaScript in `js/vet-clinic-map.js`.

## Technical Details

### Files Structure
```
vet_clinic_map/
├── config/
│   └── install/
│       └── vet_clinic_map.settings.yml
├── css/
│   └── vet-clinic-map.css
├── js/
│   └── vet-clinic-map.js
├── src/
│   ├── Controller/
│   │   └── VetClinicMapController.php
│   └── Form/
│       └── VetClinicFinderForm.php
├── templates/
│   └── vet-clinic-finder.html.twig
├── vet_clinic_map.info.yml
├── vet_clinic_map.libraries.yml
├── vet_clinic_map.module
├── vet_clinic_map.routing.yml
└── vet_clinic_map.install
```

### Dependencies
- Drupal Core (9.x or 10.x)
- jQuery
- Google Maps JavaScript API

## Troubleshooting

### Map Not Loading
- Verify your Google Maps API key is configured correctly
- Check browser console for JavaScript errors
- Ensure the Maps JavaScript API is enabled in Google Cloud Console

### No Clinics Found
- Verify your content type and field names match the expected structure
- Check that clinic content exists and is published
- Review the database query in `VetClinicMapController::getClinicsForLocation()`

### AJAX Errors
- Clear Drupal cache
- Check server error logs
- Verify routing configuration

## Support
For issues and feature requests, please check your site's logs and ensure all requirements are met.
