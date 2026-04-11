    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px; border-radius:12px; overflow:hidden;">
                    <img class="img-fluid" src="banner_image/{{ $banner_images->image_three }}" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="{{ url ('shop') }}" style="border-radius: 8px;" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div  class="product-offer mb-30" style="height: 300px; border-radius:10px; overflow:hidden;">
                    <img class="img-fluid" src="banner_image/{{ $banner_images->image_offer }}" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="{{ url ('shop') }}" style="border-radius: 8px;" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-black text-yellow-300 pr-3">Featured Products</span>
        </h2>
        <div class="row px-xl-5">

            @foreach( $posts_asc as $post )
                <div class="col-6 col-md-4 col-lg-3 pb-1">
                    <div class="product-item bd-bg bg-light mb-4 rounded" style="border-radius:10px;">
                        <div class="product-img position-relative overflow-hidden" style="border-top-left-radius:10px;border-top-right-radius:10px;">
                            <img class="img-fluid add-cart-btn rounded-top" src="product_image/{{$post->image}}" alt="" style="width:100%;height:200px;object-fit:cover;">
                            <div class="product-action">
                               
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="#">{{ $post->product }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh{{ moneyFormat($post->price) }}</h5>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <a href="{{ url('product_info', $post->id) }}" class="btn btn-primary order-now-btn rounded" data-product-id="{{ $post->id }}">
                                    Order Now
                                </a>
                            </div>
                            <a href="{{ url('product_info', $post->id) }}">
                                <h6 class="text-primary align-items-center justify-content-center mb-1">Product Details?</h6>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>