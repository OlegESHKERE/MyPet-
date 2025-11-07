/**
 * @file
 * JavaScript for Vet Clinic Map functionality.
 */

(function ($, Drupal, drupalSettings) {
  'use strict';

  // Global variables
  let map;
  let markers = [];
  let infoWindow;

  /**
   * Drupal behavior for vet clinic map.
   */
  Drupal.behaviors.vetClinicMap = {
    attach: function (context, settings) {
      
      // Initialize the map when the page loads
      if ($('#vet-clinic-map', context).length && !map) {
        initializeMap();
      }

      // Handle form submission
      $('#vet-clinic-finder-form', context).once('vet-clinic-form').on('submit', function(e) {
        e.preventDefault();
        
        const region = $(this).find('select[name="region"]').val();
        const city = $(this).find('select[name="city"]').val();
        
        loadClinics(region, city);
      });

      // Auto-submit when selections change
      $('#vet-clinic-finder-form select', context).once('auto-submit').on('change', function() {
        const $form = $(this).closest('form');
        const region = $form.find('select[name="region"]').val();
        const city = $form.find('select[name="city"]').val();
        
        // Only auto-submit if both region and city are selected
        if (region && city) {
          loadClinics(region, city);
        } else if (region && !city) {
          // If only region is selected, clear the map but don't search yet
          clearMarkers();
          updateClinicsList([]);
        }
      });
    }
  };

  /**
   * Initialize Google Map.
   */
  function initializeMap() {
    const mapOptions = {
      zoom: 8,
      center: { lat: 50.4501, lng: 30.5234 }, // Kyiv coordinates
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: [
        {
          featureType: 'poi.medical',
          elementType: 'all',
          stylers: [{ visibility: 'on' }]
        }
      ]
    };

    map = new google.maps.Map(document.getElementById('vet-clinic-map'), mapOptions);
    infoWindow = new google.maps.InfoWindow();
  }

  /**
   * Load clinics for selected region and city.
   */
  function loadClinics(region, city) {
    const url = `/vet-clinics/ajax/${encodeURIComponent(region)}/${encodeURIComponent(city)}`;
    
    // Show loading indicator
    $('#clinics-list').html('<div class="loading">Loading clinics...</div>');
    
    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          displayClinicsOnMap(response.clinics);
          updateClinicsList(response.clinics);
          
          if (response.clinics.length === 0) {
            $('#clinics-list').html('<div class="no-results">No vet clinics found in the selected area.</div>');
          }
        } else {
          console.error('Error loading clinics:', response);
          $('#clinics-list').html('<div class="error">Error loading clinics. Please try again.</div>');
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', error);
        $('#clinics-list').html('<div class="error">Error loading clinics. Please try again.</div>');
      }
    });
  }

  /**
   * Display clinics on the map.
   */
  function displayClinicsOnMap(clinics) {
    // Clear existing markers
    clearMarkers();
    
    if (clinics.length === 0) {
      return;
    }

    const bounds = new google.maps.LatLngBounds();
    
    clinics.forEach(function(clinic) {
      if (clinic.latitude && clinic.longitude) {
        const position = {
          lat: parseFloat(clinic.latitude),
          lng: parseFloat(clinic.longitude)
        };

        const marker = new google.maps.Marker({
          position: position,
          map: map,
          title: clinic.title,
          icon: {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
              <svg width="30" height="40" viewBox="0 0 30 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 0C6.716 0 0 6.716 0 15c0 8.284 15 25 15 25s15-16.716 15-25C30 6.716 23.284 0 15 0z" fill="#FF6B6B"/>
                <circle cx="15" cy="15" r="8" fill="white"/>
                <text x="15" y="19" text-anchor="middle" font-family="Arial" font-size="12" fill="#FF6B6B">+</text>
              </svg>
            `),
            scaledSize: new google.maps.Size(30, 40),
            anchor: new google.maps.Point(15, 40)
          }
        });

        // Create info window content
        const infoContent = `
          <div class="clinic-info-window">
            <h3>${clinic.title}</h3>
            ${clinic.address ? `<p><strong>Address:</strong> ${clinic.address}</p>` : ''}
            ${clinic.phone ? `<p><strong>Phone:</strong> <a href="tel:${clinic.phone}">${clinic.phone}</a></p>` : ''}
            ${clinic.email ? `<p><strong>Email:</strong> <a href="mailto:${clinic.email}">${clinic.email}</a></p>` : ''}
            <p><a href="${clinic.url}" target="_blank">View Details</a></p>
          </div>
        `;

        marker.addListener('click', function() {
          infoWindow.setContent(infoContent);
          infoWindow.open(map, marker);
        });

        markers.push(marker);
        bounds.extend(position);
      }
    });

    // Fit map to show all markers
    if (markers.length > 0) {
      map.fitBounds(bounds);
      
      // Don't zoom in too much for a single clinic
      if (markers.length === 1) {
        map.setZoom(Math.min(map.getZoom(), 14));
      }
    }
  }

  /**
   * Clear all markers from the map.
   */
  function clearMarkers() {
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];
  }

  /**
   * Update the clinics list sidebar.
   */
  function updateClinicsList(clinics) {
    let listHtml = '';
    
    if (clinics.length > 0) {
      listHtml = `<h3>Found ${clinics.length} vet clinic${clinics.length !== 1 ? 's' : ''}</h3>`;
      listHtml += '<div class="clinics-list">';
      
      clinics.forEach(function(clinic) {
        listHtml += `
          <div class="clinic-item">
            <h4><a href="${clinic.url}" target="_blank">${clinic.title}</a></h4>
            ${clinic.address ? `<p class="address">${clinic.address}</p>` : ''}
            ${clinic.phone ? `<p class="phone"><a href="tel:${clinic.phone}">${clinic.phone}</a></p>` : ''}
            ${clinic.email ? `<p class="email"><a href="mailto:${clinic.email}">${clinic.email}</a></p>` : ''}
            <p class="location">${clinic.city}, ${clinic.region}</p>
          </div>
        `;
      });
      
      listHtml += '</div>';
    }
    
    $('#clinics-list').html(listHtml);
  }

  // Initialize map when Google Maps API is loaded
  window.initMap = function() {
    if (typeof Drupal !== 'undefined' && Drupal.behaviors.vetClinicMap) {
      // Map will be initialized by the Drupal behavior
    }
  };

})(jQuery, Drupal, drupalSettings);
