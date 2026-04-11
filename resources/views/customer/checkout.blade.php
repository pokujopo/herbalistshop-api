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
                    <a class="breadcrumb-item text-dark" href="{{ url('index')}}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ url('shop')}}">Shop</a>
                    <span class="breadcrumb-item active">Checkout</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Checkout Start -->
  <form  action="">   
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Billing Address</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input class="form-control" name="first_name" type="text" placeholder="John">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" name="last_name" type="text" placeholder="Doe">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" name="email" type="text" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" name="mobile" type="text" placeholder="+123 456 789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 1</label>
                            <input class="form-control" name="address_line" type="text" placeholder="123 Street">
                        </div>
                      
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <input class="form-control" name="country" type="text" placeholder="country">
                            
                        </div>
                        <div class="col-md-6 form-group">
                            <label>City</label>
                            <input class="form-control" name="city" type="text" placeholder="Dar er salaam">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Manspal</label>
                            <input class="form-control" name="manspal" type="text" placeholder="Ilala">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" name="zip_code" type="text" placeholder="123">
                        </div>
                       
                       
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order Total</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom">
                        <h6 class="mb-3">Products</h6> 
                        @forelse ($groupedItems as $items)
                        <div class="d-flex justify-content-between">
                            <p>{{$items ->product}}</p>
                            <input type="number" hidden name="product" value="{{$items ->product}}">
                            <p> <strong>Price:</strong> ${{$items -> price}}</p>
                            <input type="number" hidden name="price" value="{{$items ->price}}">
                            <p><strong>Quantity:</strong> {{$items ->quantity}}</p>
                            <input type="number" hidden name="quantity" value="{{$items ->quantity}}">
                        </div>
                         @empty
            
                        <p>no cart found</p>
                       @endforelse
                    </div>
                    <div class="border-bottom pt-3 pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6 class="item_total">$<span id="subtotal">0</span></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Free Transfar</h6>
                            <h6 class="font-weight-medium">$0</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Total</h5>
                            <h5>$<span class="subtotal2">0</span></h5>
                            
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Payment</span></h5>
                    <div class="bg-light p-30">
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="paypal">
                                <label class="custom-control-label" for="paypal">Azampayment getway</label><br>
                                 <small>Weka mobile number:</small><input type="number" class="input" name="mobile_number" >
                            </div>
                        </div>
                      
                        <button class="btn btn-block btn-primary font-weight-bold py-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Checkout End -->




    <!-- Footer Start -->
 @include('customer.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
@include('customer.footer_link')

</body>

</html>