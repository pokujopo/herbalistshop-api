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
                            <h6 class="mb-4">Update Product</h6>

                      <form action="{{ url ('update_product', $posts->id) }}"  method="POST" enctype="multipart/form-data">
                        @csrf
                            <label for="basic-url" class="form-label">Name Of Your Product</label>
                            <div class="input-group mb-3">
                                
                                <input type="text" name="product" value="{{$posts ->product}}" class="form-control" placeholder="Product Name" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                            <label for="basic-url" class="form-label">Princing Of Your Product </label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Tsh</span>
                                <input type="number" value="{{$posts ->price}}" name="price" class="form-control" placeholder="number"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text">.00</span>
                            </div>

                            <label for="basic-url" class="form-label">Image Of Your Product</label>
                            <div class="input-group mb-3">
                                <img width="100" height="100" src="product_image/{{$posts->image}}" alt="">
                                <input type="file" name="image" value="{{$posts ->image}}" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                            <label for="basic-url" class="form-label">Category Of Your Product</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Category</span>
                                <input type="text" name="category" value="{{$posts ->category}}" class="form-control" aria-label="Amount (to the nearest dollar)">
                                
                            </div>

    
                            <label for="basic-url" class="form-label">Description Of Your Product</label>
                            <div class="input-group">
                                <span class="input-group-text">Description</span>
                                <textarea class="form-control" name="description" value="{{$posts ->description}}" aria-label="With textarea">{{$posts ->description}}</textarea>
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

                      <form action="{{ url ('post_a') }}"  method="POST" enctype="multipart/form-data">
                      @csrf
                      <label for="basic-url" class="form-label">Image Of Banner1</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="banner" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                            <label for="basic-url" class="form-label">Image Of Banner2</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="banner2" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>

                            <label for="basic-url" class="form-label">Image Of Banner3</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="banner3" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>

                            <label for="basic-url" class="form-label">Image Of offer/discount</label>
                            <div class="input-group mb-3">
                                
                                <input type="file" name="offer" class="form-control" id="basic-url" aria-describedby="basic-addon3">
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