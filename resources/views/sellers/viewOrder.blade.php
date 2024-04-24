<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cart</title>
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
</head>
<body>	
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
									<th class="column-4">Quantity</th>
									<th class="column-5">Total</th>
								</tr>

                                @php
                                    $total = 0; // Initialize total variable
                                @endphp
                                @foreach (json_decode($products) as $product)
                                <tr class="table_row">
                                    <td class="column-1">
                                        <div class="how-itemcart1">
                                            <img src="/products/{{ $product->image_url }}" alt="IMG">
                                        </div>
                                    </td>
                                    <td class="column-2">{{ $product->name }}</td>
                                    <td class="column-3">$ {{ $product->price }}</td>
                                    <td class="column-4">{{ $product->pivot->quantity }}</td> <!-- Accessing the quantity from pivot table -->
                                    <td class="column-5">$ {{ number_format($product->price * $product->pivot->quantity, 2) }}</td>
                                </tr>

                                @php
                                    // Update total by adding the price * quantity of current product
                                    $total += $product->price * $product->pivot->quantity; // Accessing quantity from pivot table
                                @endphp
                                @endforeach

							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">
							Cart Totals
						</h4>

						<div class="flex-w flex-t bor12 p-b-13">
							<div class="size-208">
								<span class="stext-110 cl2">
									Subtotal:
								</span>
							</div>

							<div class="size-209">
								<span class="mtext-110 cl2">
									$ {{ number_format($total, 2) }}
								</span>
							</div>
						</div>

						<div class="flex-w flex-t p-t-27 p-b-33">
							<div class="size-208">
								<span class="mtext-101 cl2">
									Total:
								</span>
							</div>

							<div class="size-209 p-t-1">
								<span class="mtext-110 cl2">
									$ {{ number_format($total, 2) }}
								</span>
							</div>
						</div>
                    </div>
				</div>
                <form method="GET" action="{{ route('sellers.changeOrderStatus', ['id' => $order->id]) }}">
                    @csrf
                    <button class="flex-c-m stext-101 cl0 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer" style="padding: 200px 70vh" {{ $order->order_status=="delivered"?"disabled":"" }}>
                        DELIVERED
                    </button>
                </form>
			</div>
		</div>
	</div>
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