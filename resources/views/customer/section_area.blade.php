    <div class="container-fluid rounded mb-3" >
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel" style="border-radius:12px;">
                    <ol class="carousel-indicators">
                        <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#header-carousel" data-slide-to="1"></li>
                        <li data-target="#header-carousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div  class="carousel-item position-relative active" style="height: 430px; border-radius:12px; overflow:hidden;">
                            <img style="border-radius:12px;" class="position-absolute w-100 h-100" src="banner_image/{{ $banner_images->image_one }}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3 text-center" style="max-width: 700px;">
                                    <h4 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Welcome to Bin Adam — Natural Remedies & Herbal Wellness</h4>
                                    <p class="text-white mb-2">Pure, trusted remedies crafted for your family's health.</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" style="border-radius:8px;" href="{{ url('shop') }}">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px; border-radius:12px; overflow:hidden;">
                            <img class="position-absolute w-100 h-100" src="banner_image/{{ $banner_images->image_two }}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3 text-center" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Relief for Everyday Aches</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn text-white">Fast-acting, gentle solutions made from nature — feel better today.</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" style="border-radius:8px;" href="{{ url('shop') }}">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px; border-radius:12px; overflow:hidden;">
                            <img class="position-absolute w-100 h-100" src="banner_image/{{ $banner_images->image_three }}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3 text-center" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Immune Support Essentials</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn text-white">Boost your wellbeing with trusted herbal blends and supplements.</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" style="border-radius:8px;" href="{{ url('shop') }}">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-none d-md-block">
                <div class="product-offer border-radius-8 mb-30" style="height: 200px; overflow:hidden; border-radius:12px;">
                    <img class="img-fluid w-100 h-100" src="banner_image/{{ $banner_images->image_offer }}" alt="" style="object-fit:cover;">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Limited Time — Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="{{ url('shop') }}" style="border-radius:8px;" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>

                <div class="product-offer mb-30" style="height: 200px; overflow:hidden; border-radius:12px;">
                    <img class="img-fluid w-100 h-100" src="banner_image/{{ $banner_images->image_one }}" alt="" style="object-fit:cover;">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Exclusive Picks</h6>
                        <h3 class="text-white mb-3">Best Sellers</h3>
                        <a href="{{ url('shop') }}" style="border-radius:8px;" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div  class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <a href="{{url ('shop')}}">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <a href="{{url ('shop')}}">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Older product</h5>
                </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <a href="{{url ('shop')}}">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Popural</h5>
                </div>
                    </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <a href="{{url ('contact')}}">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
                </a>
            </div>
        </div>
    </div>