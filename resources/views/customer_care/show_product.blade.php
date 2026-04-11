<!DOCTYPE html>
<html lang="en">

@include('customer_care.headerlink')

<body>
    @include('customer_care.header')
           
     <!-- Table Start -->
            
                 
     <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Products Table</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">id</th>
                                            <th scope="col">Product Name</th> 
                                            <th scope="col">Category</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">image</th>
                                            <th scope="col">Posted Date</th>
                                            <th scope="col">Delete</th>
                                             <th scope="col">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                           @foreach($posts as $post)
                                        <tr>
                                            <th scope="row">{{ $post->id }}</th>
                                            <td>{{ $post->product }}</td>
                                            <td>{{ $post->category }}</td>
                                            <td>{{ $post->price }}</td>
                                            <td>{{ $post->description }}</td>
                                            <td><img width="100" height="100" src="product_image/{{$post->image}}" alt=""></td>
                                            <td>{{ $post->created_at }}</td>
                                            <td><a href="{{ url ('delete_product', $post->id) }}" class=" btn btn-danger "  >delete</a></td>
                                            <td><a href="{{ url ('update_product_page', $post->id) }}" class=" btn btn-success "  >Update</a></td>
                                        </tr>
                            @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
            <!-- Table End -->


       @include('customer_care.footer')
</body>

</html>