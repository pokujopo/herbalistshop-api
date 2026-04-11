<!DOCTYPE html>
<html lang="en">

<head>
 @include('customer.head_link')
</head>

<body>
    <!-- Topbar Start -->
    @include('customer.navigation_bar')
    <!-- Navbar End -->


    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ url('index')}}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ url('shop')}}">Shop</a>
                    <span class="breadcrumb-item active">Shop List</span>
                </nav>
            </div>

             <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                            </div>
                            <div class="ml-2">
                                <div class="btn-group">
                                    
                                    <select id="filterByRate"  class="btn btn-sm btn-light dropdown-menu-right">
                                        <option class="dropdown-item" value="newPost" >Latest</option>
                                        <option class="dropdown-item" value="olderProduct" >Older Product</option>
                                        <option value="popular">Popularity</option>
                            
                                    </select>
                                </div>
                                <div class="btn-group ml-2">

                                    <select id="filterByNumber" class="btn btn-sm btn-light dropdown-menu-right">
                                        <option value="100" >Showing</option>
                                         <option value="2">2</option>
                                         <option value="4">4</option>
                                         <option value="6">6</option>
                                      
                                    </select>
                                </div>
                            </div>
                        </div>
                      </div> 
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
               
                <!-- Price End -->
                
                <!-- Category Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter by Category</span></h5>
                <div class="bg-light p-4 mb-30">
                    <select id="filterByCategory">
                       <option value="all">category</option>
                       <option value="virutubisho">virutubisho</option>
                       <option value="nguvu">nguvu</option>
                       <option value="MEDICAL">medicine</option>
                    </select>
                </div>
                <!-- Category End -->

              
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
         
                @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1" >
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/{{$product -> image}}" alt="">
                            <div class="product-action">
                                  <button class="btn btn-outline-dark btn-square add-to-cart-btn" data-product-id="{{$product ->id}}" >
                                <i class="fa fa-shopping-cart"></i></button>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">price: Tsh{{ moneyFormat($product -> price) }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5> </h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="{{url('see_cart_product')}}"><button data-product-id="{{$product ->id}}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="{{url('product_info', $product->id)}}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                @endforeach
           
              <div class="col-12">
                        <nav>
                          <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</span></a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                          </ul>
                        </nav>
                    </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->


    <!-- Footer Start -->
 @include('customer.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
   @include('customer.footer_link')
</body>

</html>