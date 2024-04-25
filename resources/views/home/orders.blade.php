<!DOCTYPE html>
<html lang="en">
<head>
	<title>Buyer Orders</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
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
<body class="animsition">
@include('home.header')
    <div class="container">
        <h1 class="mt-3">Buyer Orders</h1>
        <div class="mt-3">
            @if(count($orders) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Total</th>
                            <th>Payement Status</th>
                            <th>Order Status</th>
                            <th>Buyer Name</th>
                            <th>Buyer Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>$ {{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->order_status }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No Orders found.</p>
            @endif
        </div>
    </div>
    
@include('home.footer')
<!-- Include Bootstrap JS (Optional, if you need Bootstrap JavaScript features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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


