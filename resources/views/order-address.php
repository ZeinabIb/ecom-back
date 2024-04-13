<!DOCTYPE html>
<html>
  <head>
    <title>Address Selection</title>
    <style>
      body {
        margin: 0 auto;
      }

      .sb-title {
        position: relative;
        top: -12px;
        font-family: Roboto, sans-serif;
        font-weight: 500;
      }

      .sb-title-icon {
        position: relative;
        top: -5px;
      }

      .card-container {
        display: flex;
        height: 500px;
        width: 600px;
        margin: 0 auto;
        margin-top: 10px;
      }

      .panel {
        background: white;
        width: 300px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
      }

      .half-input-container {
        display: flex;
        justify-content: space-between;
      }

      .half-input {
        max-width: 120px;
      }

      .map {
        width: 300px;
      }

      h2 {
        margin: 0;
        font-family: Roboto, sans-serif;
      }

      input {
        height: 30px;
      }

      input {
        border: 0;
        border-bottom: 1px solid black;
        font-size: 14px;
        font-family: Roboto, sans-serif;
        font-style: normal;
        font-weight: normal;
      }

      input:focus::placeholder {
        color: white;
      }

      .button-cta {
        align-self: start;
        background-color: #1976d2;
        border: 0;
        border-radius: 21px;
        color: white;
        cursor: pointer;
        font-family: "Google Sans Text", sans-serif;
        font-size: 14px;
        font-weight: 500;
        line-height: 27px;
        padding: 3.5px 10.5px;
      }

      .custom-map-control-button {
        align-self: start;
        background-color: #1976d2;
        border: 0;
        border-radius: 21px;
        color: white;
        cursor: pointer;
        font-family: "Google Sans Text", sans-serif;
        font-size: 14px;
        font-weight: 500;
        line-height: 27px;
        padding: 3.5px 10.5px;
        margin-top: 10px;
      }

      .custom-map-control-button:hover {
        background: rgb(50, 100, 200);
      }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Google+Sans+Text:500&amp;lang=en">
    <script>
      "use strict";

      const CONFIGURATION = {
        "ctaTitle": "Checkout",
        "mapOptions": {"center":{"lat":37.4221,"lng":-122.0841},"fullscreenControl":true,"mapTypeControl":false,"streetViewControl":true,"zoom":11,"zoomControl":true,"maxZoom":22,"mapId":""},
        "mapsApiKey": "AIzaSyAxWvBQQy-KxHaFkQXbo0dwPiyRy-rHTP8",
        "capabilities": {"addressAutocompleteControl":true,"mapDisplayControl":true,"ctaControl":true}
      };

      const SHORT_NAME_ADDRESS_COMPONENT_TYPES =
          new Set(['street_number', 'administrative_area_level_1', 'postal_code']);

      const ADDRESS_COMPONENT_TYPES_IN_FORM = [
        'location',
        'locality',
        'administrative_area_level_1',
        'postal_code',
        'country',
      ];

      function getFormInputElement(componentType) {
        return document.getElementById(`${componentType}-input`);
      }

      function fillInAddress(place) {
        function getComponentName(componentType) {
          for (const component of place.address_components || []) {
            if (component.types[0] === componentType) {
              return SHORT_NAME_ADDRESS_COMPONENT_TYPES.has(componentType) ?
                  component.short_name :
                  component.long_name;
            }
          }
          return '';
        }

        function getComponentText(componentType) {
          return (componentType === 'location') ?
              `${getComponentName('street_number')} ${getComponentName('route')}` :
              getComponentName(componentType);
        }

        for (const componentType of ADDRESS_COMPONENT_TYPES_IN_FORM) {
          getFormInputElement(componentType).value = getComponentText(componentType);
        }
      }

      function renderAddress(place, map, marker) {
        if (place.geometry && place.geometry.location) {
          map.setCenter(place.geometry.location);
          marker.position = place.geometry.location;
        } else {
          marker.position = null;
        }
      }

      async function initMap() {
        const {Map} = google.maps;
        const {AdvancedMarkerElement} = google.maps.marker;
        const {Autocomplete} = google.maps.places;

        const mapOptions = CONFIGURATION.mapOptions;
        mapOptions.mapId = mapOptions.mapId || 'DEMO_MAP_ID';
        mapOptions.center = mapOptions.center || {lat: 37.4221, lng: -122.0841};

        const map = new Map(document.getElementById('gmp-map'), mapOptions);
        const marker = new AdvancedMarkerElement({map});
        const autocomplete = new Autocomplete(getFormInputElement('location'), {
          fields: ['address_components', 'geometry', 'name'],
          types: ['address'],
        });

        autocomplete.addListener('place_changed', () => {
          const place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert(`No details available for input: '${place.name}'`);
            return;
          }
          renderAddress(place, map, marker);
          fillInAddress(place);
        });

        const locationButton = document.createElement("button");

        locationButton.textContent = "Pan to Current Location";
        locationButton.classList.add("custom-map-control-button");
        document.querySelector('.panel').appendChild(locationButton);

        locationButton.addEventListener("click", () => {
          // Try HTML5 geolocation.
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                const pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
                };

                map.setCenter(pos);
                marker.position = pos;

                console.log("Current Location:", pos);
              },
              () => {
                window.alert("Geolocation failed.");
              }
            );
          } else {
            // Browser doesn't support Geolocation
            window.alert("Your browser doesn't support geolocation.");
          }
        });
      }
    </script>
  </head>
  <body>
    <div class="card-container">
      <div class="panel">
        <div>
          <img class="sb-title-icon" src="https://fonts.gstatic.com/s/i/googlematerialicons/location_pin/v5/24px.svg" alt="">
          <span class="sb-title">Address Selection</span>
        </div>
        <input type="text" placeholder="Address" id="location-input"/>
        <input type="text" placeholder="Apt, Suite, etc (optional)"/>
        <input type="text" placeholder="City" id="locality-input"/>
        <div class="half-input-container">
          <input type="text" class="half-input" placeholder="State/Province" id="administrative_area_level_1-input"/>
          <input type="text" class="half-input" placeholder="Zip/Postal code" id="postal_code-input"/>
        </div>
        <input type="text" placeholder="Country" id="country-input"/>
        <button class="button-cta">Checkout</button>
      </div>
      <div class="map" id="gmp-map"></div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWvBQQy-KxHaFkQXbo0dwPiyRy-rHTP8&libraries=places,marker&callback=initMap&solution_channel=GMP_QB_addressselection_v2_cABC" async defer></script>
  </body>
</html>
