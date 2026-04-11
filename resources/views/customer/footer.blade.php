   <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">Click here to download app yetu</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>234 songea, dar el salaam, TANZANIA</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="{{url('/')}}"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-2" href="{{url('/shop')}}"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            @if (Route::has('login'))
                             @auth
                             <a class="text-secondary mb-2" href="{{ route('logout') }}"><i class="fa fa-angle-right mr-2"></i>logout</a>


                            @else
                             <a class="text-secondary mb-2" href="{{ route('login') }}"><i class="fa fa-angle-right mr-2"></i>login</a>
                            <a class="text-secondary mb-2" href="{{ route('register') }}"><i class="fa fa-angle-right mr-2"></i>register</a>
                            @endauth

                        @endif  
                            <a class="text-secondary" href="{{url('/contact')}}"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">NewsLetter</h5>
                        <p>Add your email to get news</p>

                        @guest
                            <div class="mb-3">
                                <a href="{{ route('register') }}" class="btn btn-primary">Register to Subscribe</a>
                            </div>
                        @else
                            @if(session('newsletter_success'))
                                <div class="alert alert-success py-1 px-2">{{ session('newsletter_success') }}</div>
                            @endif

                            <form action="/newsletter/subscribe" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <input
                                        name="email"
                                        type="email"
                                        class="form-control"
                                        placeholder="Your Email Address"
                                        value="{{ old('email') ?? Auth::user()->email }}"
                                        required
                                    >
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Sign Up</button>
                                    </div>
                                </div>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </form>

                            <p class="text-success mb-2">We a familia</p>
                        @endguest

                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>   </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="#">2026</a>. All Rights Reserved. Designed
                    by
                    <a class="text-primary" href="#">JboyCreator</a>
                    
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>