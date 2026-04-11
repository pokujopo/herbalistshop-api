   
    <!-- Categories End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-black pr-3">Recent Products</span></h2>
        <div class="row px-xl-5">
            @foreach( $posts as $post )    
            <div class="col-6 col-md-4 col-lg-3 pb-1">
                <div class="product-item bd-bg bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid add-cart-btn" src="product_image/{{$post->image}}" alt="">
                        <div class="product-action">
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate" href="#">{{ $post->product }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>Tsh{{ moneyFormat($post->price) }}</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <a href="{{ url('product_info', $post->id) }}" class="btn align-items-center justify-content-center mb-1 btn-primary" style="border-radius:8px;">Order Now</a>
                        </div>
                        <a href="{{ url('product_info', $post->id) }}"><h6 class="text-primary align-items-center justify-content-center mb-1">Product Details?</h6></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        </div>
