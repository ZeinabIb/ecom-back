<!DOCTYPE html>
<html lang="en">
<head>
	<title>Wish List</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

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
          width: 100%;
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
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="/images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/bootstrap/css/bootstrap.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/fonts/iconic/css/material-design-iconic-font.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/fonts/linearicons-v1.0.0/icon-font.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/animate/animate.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/css-hamburgers/hamburgers.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/animsition/css/animsition.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/select2/select2.min.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/daterangepicker/daterangepicker.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/slick/slick.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/MagnificPopup/magnific-popup.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/vendor/perfect-scrollbar/perfect-scrollbar.css')}}>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href={{ asset('home/css/util.css')}}>
	<link rel="stylesheet" type="text/css" href={{ asset('home/css/main.css')}}>
<!--===============================================================================================-->
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

      const locationButton = document.createElement("a");

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

              document.getElementById('location_lat_cash').value = pos.lat;
              document.getElementById('location_lng_cash').value = pos.lng;

              document.getElementById('location_lat_card').value = pos.lat;
              document.getElementById('location_lng_card').value = pos.lng;
              
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
<body class="animsition">
    @include('home.header')

	<!-- Cart -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>
	</div>


	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="{{ route('home.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Wish List
			</span>
		</div>
	</div>
		

	<!-- Shoping Cart -->
	<div class="bg0 p-t-75 p-b-85">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">
							<table class="table-shopping-cart">
								<tr class="table_head">
									<th class="column-1">Product</th>
									<th class="column-2"></th>
									<th class="column-3">Price</th>
									<th class="column-5">Action</th>
								</tr>

                                @php
                                    $total = 0; // Initialize total variable
                                @endphp
                                @foreach (Auth::user()->wishlist->products as $productInWishList)
                                <tr class="table_row">
                                    <td class="column-1">
                                        <div class="how-itemcart1">
                                            <img src="/products/{{ $productInWishList->image_url }}" alt="IMG">
                                        </div>
                                    </td>
                                    <td class="column-2">{{ $productInWishList->name }}</td>
                                    <td class="column-3">$ {{ $productInWishList->price }}</td>
                                    <td class="column-6">
                                       <a href="{{ route('user.removeFromWishlist', ['id'=>$productInWishList->id]) }}" class="btn btn-primary text-white">Remove</a>              
                                    </td>
                                </tr>

                                @php
                                @endphp
                                @endforeach

							</table>
						</div>
					</div>
				</div>       
            </form>

            </form>
          </div>
				</div>
			</div>
		</div>

        <div class="panel"></div>

	</div>
    @include('home.footer')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWvBQQy-KxHaFkQXbo0dwPiyRy-rHTP8&libraries=places,marker&callback=initMap&solution_channel=GMP_QB_addressselection_v2_cABC" async defer></script>
    <!--===============================================================================================-->
	<script src="/home/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
        <script src="/home/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
        <script src="/home/vendor/bootstrap/js/popper.js"></script>
        <script src="/home/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
        <script src="/vendor/select2/select2.min.js"></script>
        <script>
            $(".js-select2").each(function(){
                $(this).select2({
                    minimumResultsForSearch: 20,
                    dropdownParent: $(this).next('.dropDownSelect2')
                });
            })
        </script>
    <!--===============================================================================================-->
        <script src="/home/vendor/daterangepicker/moment.min.js"></script>
        <script src="/home/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
        <script src="/home/vendor/slick/slick.min.js"></script>
        <script src="/home/js/slick-custom.js"></script>
    <!--===============================================================================================-->
        <script src="/home/vendor/parallax100/parallax100.js"></script>
        <script>
            $('.parallax100').parallax100();
        </script>
    <!--===============================================================================================-->
        <script src="/home/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
        <script>
            $('.gallery-lb').each(function() { // the containers for all your galleries
                $(this).magnificPopup({
                    delegate: 'a', // the selector for gallery item
                    type: 'image',
                    gallery: {
                        enabled:true
                    },
                    mainClass: 'mfp-fade'
                });
            });
        </script>
    <!--===============================================================================================-->
        <script src="/home/vendor/isotope/isotope.pkgd.min.js"></script>
    <!--===============================================================================================-->
        <script src="/home/vendor/sweetalert/sweetalert.min.js"></script>
        <script>
            $('.js-addwish-b2').on('click', function(e){
                e.preventDefault();
            });

            $('.js-addwish-b2').each(function(){
                var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
                $(this).on('click', function(){
                    swal(nameProduct, "is added to wishlist !", "success");

                    $(this).addClass('js-addedwish-b2');
                    $(this).off('click');
                });
            });

            $('.js-addwish-detail').each(function(){
                var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

                $(this).on('click', function(){
                    swal(nameProduct, "is added to wishlist !", "success");

                    $(this).addClass('js-addedwish-detail');
                    $(this).off('click');
                });
            });

            /*---------------------------------------------*/

            $('.js-addcart-detail').each(function(){
                var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
                $(this).on('click', function(){
                    swal(nameProduct, "is added to cart !", "success");
                });
            });

        </script>
    <!--===============================================================================================-->
        <script src="home/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script>
            $('.js-pscroll').each(function(){
                $(this).css('position','relative');
                $(this).css('overflow','hidden');
                var ps = new PerfectScrollbar(this, {
                    wheelSpeed: 1,
                    scrollingThreshold: 1000,
                    wheelPropagation: false,
                });

                $(window).on('resize', function(){
                    ps.update();
                })
            });
        </script>

    <!--===============================================================================================-->
        <script src="/home/js/main.js"></script>

     

</body>
</html>


