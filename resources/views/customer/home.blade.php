<!DOCTYPE html>
<html lang="en">

@include('customer.head_link')

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


    <!-- Carousel Start -->
@include('customer.section_area')
    <!-- Featured End -->


    <!-- Categories Start -->
@include('customer.main_page')
    <!-- Products End -->


    <!-- Offer Start -->
@include('customer.center_page')
    <!-- Products End -->


    <!-- Vendor Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-1.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-2.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-3.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-4.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-5.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-6.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-7.jpg" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="customer/img/vendor-8.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->


    <!-- Footer Start -->
 @include('customer.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
@include('customer.footer_link')
</body>

</html>