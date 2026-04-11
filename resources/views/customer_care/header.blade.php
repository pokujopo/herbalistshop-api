<div class="container-xxl position-relative bg-white d-flex p-0">
      
   <!--     <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
      


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ABUHERBAL</h3>
                </a>
                 @php
                $name = Auth::user()->name ?? '';
                $initial = $name !== '' ? mb_strtoupper(mb_substr($name, 0, 1)) : '?';
              @endphp
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <span class="rounded-circle bg-green-700 p-3 font-semibold" style="width: 40px; height: 40px; background-color:red;">{{ $initial }}</span>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>{{ Auth::user()->usertype }}</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="{{url ('/')}}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>View Site</a>
                    
                    <a href="{{ url ('post_product_page')}}" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
                    <a href="{{ url ('show_product_page')}}" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Product Tables </a>
                    <a href="{{ route('logout') }}" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i> logout </a>
                    
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a  class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                   
                    
                    <div class="nav-item dropdown">
                        
                        <span class="rounded-circle me-lg-2 bg-green-700 p-2 font-semibold" style="width: 40px; height: 40px; background-color:red;">{{ $initial }}</span> <span class=" text-uppercase ">{{ Auth::user()->name }}</span>
                            
                     
                        
                    </div>
                </div>
            </nav>