<!DOCTYPE html>
<html lang="en">

@include('customer_care.headerlink')

<style>

.form_css{

text-align:center;
  padding: 30px;
}
</style>

<body>
    @include('customer_care.header')
           
     

    
            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4">

           
                    
    
              <div class="row g-4">

              <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Post Product</h6>

                      <form action="{{ url ('post_product') }}"  method="POST" enctype="multipart/form-data">
                        @csrf
                            <label for="basic-url" class="form-label">Name Of Your Product</label>
                            <div class="input-group mb-3">
                                
                                <input type="text" name="product" class="form-control" placeholder="Product Name" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                            <label for="basic-url" class="form-label">Princing Of Your Product </label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Tsh</span>
                                <input type="number" name="price" class="form-control" placeholder="number"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text">.00</span>
                            </div>

                            <label for="basic-url" class="form-label">Image Of Your Product</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="image" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                            <label for="basic-url" class="form-label">Category Of Your Product</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Category</span>
                                <input type="text" name="category" class="form-control" aria-label="Amount (to the nearest dollar)">
                                
                            </div>

    
                            <label for="basic-url" class="form-label">Description Of Your Product</label>
                            <div class="input-group">
                                <span class="input-group-text">Description</span>
                                <textarea class="form-control" name="description" aria-label="With textarea"></textarea>
                            </div>


                           

                            

                          <div class=" form_css">

                    <input type="submit" name="submit" class="btn btn-primary">
                    </div>

                        </form>
                   </div>
                        </div>


                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Change Banner Appearence</h6>

                      <form action="{{ url ('change_banner_image') }}"  method="POST" enctype="multipart/form-data">
                      @csrf
                      <label for="basic-url" class="form-label">Image Of Banner1</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="image_one" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                            <label for="basic-url" class="form-label">Image Of Banner2</label>
                            <div class="input-group mb-3">
                                <img width="100" height="100" src="banner_image/{{$show_banner_img-> image_two}}>"
                                <input type="file" value="{{$show_banner_img-> image_two}}" name="image_two" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>

                            <label for="basic-url" class="form-label">Image Of Banner3</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="image_three" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>

                            <label for="basic-url" class="form-label">Image Of offer/discount</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="image_offer" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>

                            <div class=" form_css mb-3">

                        <input type="submit" name="submit" class="btn btn-primary">
                        </div>

    
            

                        </form>
                   </div>
                        </div>
                        </div>
                        </div>
                   
            
            
            <!-- Form End -->
            
                 
    


       @include('customer_care.footer')
</body>

</html>