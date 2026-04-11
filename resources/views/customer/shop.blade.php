<!DOCTYPE html>
<html lang="en">

<head>
 @include('customer.head_link')
</head>

<body>
    @php
function moneyFormat($number){
       if($number >= 1000000){
          return round($number/1000000,1).'M';
       } elseif($number >= 1000){
        return round($number/1000,1).'K';
       }

       return $number;
}
@endphp
    <!-- Topbar Start -->
    @include('customer.navigation_bar')
    <!-- Navbar End -->


    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ url('/')}}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ url('shop')}}">Shop</a>
                    <span class="breadcrumb-item active">Shop List</span>
                </nav>
            </div>

             <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button hidden class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button hidden class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
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
                <h5 class="section-title text-gray-900 position-relative text-uppercase mb-3"><span class="bg-black pr-3">Filter by Category</span></h5>
                <div class="bg-black btn-group ml-2 p-4 mb-30">
                    <select class="btn btn-sm btn-light dropdown-menu-right" id="filterByCategory">
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
            <div class="col-lg-9 col-md-8">
                <div id="postContainer" class="row pb-3">
                    @if(isset($products) && $products->count())
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card border-0">
                                    <a href="{{ url('product/'.$product->id) }}">
                                        <img class="card-img-top" src="{{ $product->image ?? asset('images/default-product.png') }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="card-body text-center">
                                        <h6 class="mb-2">{{ $product->name }}</h6>
                                        <p class="text-muted mb-2">Price: {{ moneyFormat($product->price) }}</p>
                                        <a href="{{ url('product/'.$product->id) }}" class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info mb-0">No products found.</div>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        @if(isset($products) && ($products instanceof \Illuminate\Contracts\Pagination\Paginator || $products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator))
                            <nav>
                                {!! $products->appends(request()->query())->links('pagination::bootstrap-4') !!}
                            </nav>
                        @endif
                    </div>
                </div>
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