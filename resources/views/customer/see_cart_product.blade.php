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
                    <span class="breadcrumb-item active">Shopping Cart</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Cart Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-light table-borderless table-hover text-center mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">

                     
                        @forelse ($groupedItems as $items)
                        <tr> 
                        
                            <td class="align-middle"><img src="product_image/{{$items->image}}" alt=""
                             style="width: 50px;">{{$items->product}}</td>
                            <td class="align-middle price">{{$items->price}}</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">

                                    <div class="input-group-btn">
                                        <a href="{{ url ('delete_cart_product', $items->id)}}" id="minus" class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </a>
                                    </div>

                                    <input type="text" id="take_current_value" class="form-control form-control-sm 
                                    bg-secondary border-0 text-center quantity-input" value="{{$items ->quantity}}">

                                    <div class="input-group-btn">
                                        <button id="plus"  data-product-id="{{$items->id}}" class="add-to-cart-btn  btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                </div>
                            </td>
                            <td class="align-middle item_total" > ${{$items->price}} </td>
                            <td class="align-middle">
                                <a href="{{ url ('delete_cart', $items->id)}}" class="btn btn-sm btn-danger">
                                <i class="fa fa-times"></i></a>
                            </td>

                                
                        </tr>

                        @empty
                        <tr>

                        <td><p>no cart found</p></td>

                        </tr>
                                
                                @endforelse
                        
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form class="mb-30" action="">
                    <div class="input-group">
                        <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6> $<span id="subtotal">0</span></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Free Transfare</h6>
                            <h6 class="font-weight-medium">$o</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Total</h5>
                            <h5>$ <span id="carttotal">0</span></h5>
                        </div>
                        <a href="{{ url ('proceed_checkout') }}" class="btn btn-block 
                        btn-primary font-weight-bold my-3 py-3">Proceed To Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->


    <!-- Footer Start -->
     @include('customer.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>



    @include('customer.footer_link')



</body>

</html>