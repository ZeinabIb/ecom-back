<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
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
@extends('home.header')

@section('content')
       <div class="bg0 m-t-23 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Top Stores
					</button>
				</div>

				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						{{-- <form class="bor8 dis-flex p-l-15" action="{{ route('store.filtered') }}" method="GET">
							@csrf
							<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
								<i class="zmdi zmdi-search"></i>
							</button>

							<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search_store" id="search_store" placeholder="Search">
						</form> --}}
					</div>
				</div>
			</div>

			<!-- Banner -->
	<div class="sec-banner bg0 p-t-80 p-b-50">
		<div class="container">
			<div class="row">
                @foreach ($all_stores as $store)
                    <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="/images/store-clipart.jpg" alt="IMG-BANNER">

                            <a href="{{ route('store.view', ['store_id'=>$store->id]) }}" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                       {{ $store->name }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $store->details }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
			</div>
		</div>
	</div>

    <div class="bg0 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52 p-t-4">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Top Products
					</button>
				</div>
			</div>

			<div class="row isotope-grid">

                @foreach ($all_products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->category->name}}">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="/products/{{ $product->image_url }}" alt="IMG-PRODUCT" height="350px" style="object-fit:contain; width: 100%; height: 250px;">
								<a href="{{ route('home.viewProduct', ['store_id'=>$product->store->id, 'product_id'=>$product->id]) }}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                    Quick View
                                </a>
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $product->name }}
                                    </a>
                                    <span class="stext-105 cl3">
                                        @php
                                            $exchangeRates = AshAllenDesign\LaravelExchangeRates\Facades\ExchangeRate::convert($product->price, 'USD', Auth::user()->currency , Carbon\Carbon::now())
                                        @endphp
                                        {{ number_format($exchangeRates, 2)}}
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                     @if(isset(Auth::user()->wishlist->products) && Auth::user()->wishlist->products->contains($product->id))
                                 <form action="{{ route('user.removeFromWishlist', ['id'=>$product->id]) }}" method="GET">
                                    @csrf
                                <button type="submit" class="btn-addwish-b2 dis-block pos-relative" data-tooltip="Remove from Wishlist">
                                    <img class="icon-heart1 dis-block trans-04 ab-t-l" src="/home/images/icons/icon-heart-02.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 " src="/home/images/icons/icon-heart-01.png" alt="ICON">
                                </button>
                                 </form>
                                 @else
                                 <form action="{{ route('user.addToWishlist', ['store_id'=>$product->store->id, 'product_id'=>$product->id]) }}" method="POST">
                                    @csrf
                                <button type="submit" class="btn-addwish-b2 dis-block pos-relative" data-tooltip="Add to Wishlist">
                                  <img class="icon-heart1 dis-block trans-04" src="/home/images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/home/images/icons/icon-heart-02.png" alt="ICON">
                                </button>
                                 </form>
                                 @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

			
		</div>
	</div>
    @endsection

    @section('footer')
        @include('home.footer')
    @endsection


    <!--===============================================================================================-->
	<script src="home/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
        <script src="home/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
        <script src="home/vendor/bootstrap/js/popper.js"></script>
        <script src="home/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
        <script src="vendor/select2/select2.min.js"></script>
        <script>
            $(".js-select2").each(function(){
                $(this).select2({
                    minimumResultsForSearch: 20,
                    dropdownParent: $(this).next('.dropDownSelect2')
                });
            })
        </script>
    <!--===============================================================================================-->
        <script src="home/vendor/daterangepicker/moment.min.js"></script>
        <script src="home/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
        <script src="home/vendor/slick/slick.min.js"></script>
        <script src="home/js/slick-custom.js"></script>
    <!--===============================================================================================-->
        <script src="home/vendor/parallax100/parallax100.js"></script>
        <script>
            $('.parallax100').parallax100();
        </script>
    <!--===============================================================================================-->
        <script src="home/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
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
        <script src="home/vendor/isotope/isotope.pkgd.min.js"></script>
    <!--===============================================================================================-->
        <script src="home/vendor/sweetalert/sweetalert.min.js"></script>
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
        <script src="home/js/main.js"></script>
</body>

<!--
    <script>
     var botmanWidget = {
         aboutText: 'ssdsd',
         introMessage: "✋ Hi! I'm form ItSolutionStuff.com"
     };
    </script>

    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> -->
    <link rel="stylesheet" href="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/themes/df-messenger-default.css">
<script src="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/df-messenger.js"></script>
<df-messenger
  location="us-west1"
  project-id="fresh-booster-420114"
  agent-id="39067796-12b3-403e-ab1d-469418d12d0d"
  language-code="en"
  max-query-length="-1">
  <df-messenger-chat-bubble
   chat-title="">
  </df-messenger-chat-bubble>
</df-messenger>
<style>
  df-messenger {
    z-index: 999;
    position: fixed;
    --df-messenger-font-color: #000;
    --df-messenger-font-family: Google Sans;
    --df-messenger-chat-background: #f3f6fc;
    --df-messenger-message-user-background: #d3e3fd;
    --df-messenger-message-bot-background: #fff;
    bottom: 16px;
    right: 16px;
  }
</style>
</html>
