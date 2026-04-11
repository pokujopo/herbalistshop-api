<!DOCTYPE html>
<html lang="en">

@include('customer.head_link')

<body>
    <!-- Topbar Start -->
@include('customer.navigation_bar')
    <!-- Navbar End -->
 


    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{url('/')}}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{url ('/shop')}}">Shop</a>
                    <span class="breadcrumb-item active">Shop Detail</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" >
                    <div class="carousel-inner bg-light">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="product_image/{{$posts->image}}" alt="Image">
                        </div>
                       
                    </div>
                   
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{ $posts -> product }}</h3>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(<span class="countComment">0</span> Reviews)</small>
                    </div>
                    <h3 class="font-weight-semi-bold mb-4">Price: Tsh {{ $posts -> price }}.00</h3>
                    <p class="mb-4"><strong> Description about product:</strong> {{ \Illuminate\Support\Str:: words($posts -> description, 5, '....')}}  </p>

                    <div class="d-flex mb-3">
                         <p><strong class="text-dark mr-3">Category:</strong>{{$posts -> category}}</p>             
                    </div>
                   
                    <div class="d-flex align-items-center mb-4 pt-2">
                      
                       

                                <a style="padding-left: 5px; "
                                 href="https://wa.me/255786584974?text={{urlencode( 'hello , Naitaji kununua hii bidhaa '
                                 .$posts->product.' for '.'Tsh '.$posts->price.' View image: '.asset($posts->image))}}" target="_blank">
                            <button data-product-id="{{$posts->id}}" class=" btn btn-success px-3">
                                <i class="fa fa-shopping-cart mr-1"></i>Order Via WathsApp</button></a>
                    </div>
                    <div class="d-flex pt-2">
                        <strong class="text-dark mr-2">Share on:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Description</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-3">Reviews <span class="countComment">0</span></a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Product Description</h4>
                                <p>{{$posts -> description}}</p>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-2">
                            <h4 class="mb-3">More Information</h4>
                            <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea.
                                 Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero
                                 </p>
   
                        </div>
                      
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row">
                                <div  class="col-md-6 overflow-auto" style="max-height:300px;">
                                    <h4 class="mb-4"><span class="countComment">0</span> review for "{{$posts -> product}}"</h4>
                                    <div id="commentDiv">

                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    @auth
                                        <h4 class="mb-4">Leave a comment</h4>
                                        <form id="postComments" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="message">Your Comment *</label>
                                                <textarea id="message" name="comment" class="form-control" rows="4"></textarea>
                                            </div>

                                            <input type="hidden" name="product_id" value="{{ $posts->id }}">
                                            <input type="hidden" name="product_name" value="{{ $posts->product }}">
                                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                            <input type="hidden" name="username" value="{{ auth()->user()->name }}">

                                            <div class="form-group mb-0">
                                                <input type="submit" data-id="{{ $posts->id }}" value="Leave Your Comment" class="loadComent btn btn-primary px-3">
                                            </div>
                                        </form>
                                    @else
                                        <h4 class="mb-4">Leave a comment</h4>
                                        <p>Please <a href="{{ url('login') }}?redirect={{ urlencode(url()->full()) }}">login</a> to leave a comment.</p>
                                        <a href="{{ url('login') }}?redirect={{ urlencode(url()->full()) }}" class="btn btn-primary">Login to Comment</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">You May Also Like</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">

                @forelse( $products as $product)
                    <div class="product-item bd-bg bg-light">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100 add-cart-btn" src="product_image/{{$product->image}}" alt="">
                            <div class="product-action">
                                  <button class="btn btn-outline-dark btn-square add-to-cart-btn" data-product-id="{{$product->id}}" >
                                <i class="fa fa-shopping-cart"></i></button>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">{{ $product ->product }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh {{$product->price}}.00</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="{{url('product_info', $product->id)}}"><button data-product-id="{{$product->id}}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="{{url('product_info', $product->id)}}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                    @empty

                    <p>no product found in database</p>
                    @endforelse
                
                
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->


    <!-- Footer Start -->
 @include('customer.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
  @include('customer.footer_link')

<script>
            document.getElementById('postComments').addEventListener('submit', 
                function(){
                      event.preventDefault();
                    const formData = new FormData(this);
                    console.log(formData);
                    fetch('/post_comment', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data.status);
                        display_comments();
                    })
                }
            );
            
            function display_comments(){
                const postId = '{{$posts->id}}';
                console.log(postId);
                fetch(`/get_comments/${postId}`)
                .then(res => res.json())
                .then(data => {
                    console.log(data.comments)
                    const container = document.getElementById('commentDiv');
                    let countComment = document.querySelectorAll('.countComment');
                    //const countComments = document.getElementById('countComments');
                    countComment.forEach(element => element.innerHTML = `${data.count}`);
                    container.innerHTML = ``;
                    data.comments.forEach(comment => {

                                      
                     const Initial = comment.username.charAt(0).toUpperCase();
                    container.innerHTML += `
                                    
                            
                                    
                                    <div class="media mb-4">
                                        <div  class="img-fluid mr-3" style="width: 40px; height: 40px;
                                         background-color: #007bff;
                                         padding-left: 15px;
                                         color:white;
                                         display:flex;
                                         align-items: center;
                                         justfy-content: center;
                                         font-weght: bold; 
                                         border-radius: 50%;"
                                         >${Initial} </div>
                                        <div class="media-body">
                                            <h6>${ comment.username }<small> - <i>${comment.created_at}</i></small></h6>
                                          
                                            <p><small>commented:</small> ${comment.comment.length > 50 ?comment.comment.split("").slice(0,30).join("")+"...":comment.comment}.</p>
                                        </div>
                                    </div>
                                   
                    
                    `});
                })
            }

           display_comments(); 
</script>
</body>

</html>