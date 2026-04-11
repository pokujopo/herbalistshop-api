    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="customer/lib/easing/easing.min.js"></script>
    <script src="customer/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="customer/mail/jqBootstrapValidation.min.js"></script>
    <script src="customer/mail/contact.js"></script>
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js')
    .then(() => console.log('SW registered'))
    .catch(err => console.log('SW registration failed', err));
}
</script>

    <!-- Template Javascript -->
    <script src="customer/js/main.js"></script>
    <script>
    
</script>

    <script>

        addEventListener('DOMContentLoaded', function(){
            const searchInput = document.getElementById('search-input');
            if(searchInput){
                searchInput.addEventListener("keyup", function(){
                    console.log("ok is connect");

                        const query = this.value;
                        console.log(query);
                            if(query.length > 0){
                            fetch(`/search?query=${query}`)
                            .then(res => res.json())
                            .then(data => {
                                let result = '';
                                let resultbox = document.getElementById("searchResults");
                                

                                data.product.forEach(product => {
                                     const addUrlInfo = `{{url ('product_info')}}`;
                                    result += `<a href="${addUrlInfo}/${product.id}"> <small>You looking for:</small> ${product.product}</a><br><br>`;
                                });
                                resultbox.innerHTML = result;
                                 

                            

                               
                            });

                        } else{
                            document.getElementById("searchResults").innerHTML = '';
                        }
                       
                            });
            }
        });

    
        document.getElementById('filterByCategory').addEventListener('change', 
            function () {
                const count = this.value;
                const limit = 6;
                console.log(count);
                fetch(`/get-posts-category?category=${count}&limit=${limit}/`)
                .then(res => res.json())
                .then(data => 
                   { 
                    const addUrlInfo = `{{url ('product_info')}}`;
                    const addUrlCart = `{{url ('see_cart_product')}}`;
                    const container = document.getElementById('postContainer');
                    container.innerHTML = ``;
                    data.posts.forEach(post => container.innerHTML += `

                    

                    <div class="col-lg-3 col-md-4 col-6 pb-1" > 
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/${post.image}" alt="">
                            <div class="product-action">
                                  
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">${ post.product }</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5> Tsh${moneyFormat(post.price)}</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="${addUrlInfo}/${post.id}"><button style="border-radius: 8px;" data-product-id="${post.id}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="${addUrlInfo}/${post.id}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                    
                    
                    `);


            });
            }
        )

        // helper to format large numbers into compact form like 1.2k, 3.4m
        function moneyFormat(value) {
            const num = Number(value);
            if (isNaN(num)) return value;
            const abs = Math.abs(num);
            if (abs >= 1.0e12) return (num / 1.0e12).toFixed(2).replace(/\.00$/,'') + 'T';
            if (abs >= 1.0e9) return (num / 1.0e9).toFixed(2).replace(/\.00$/,'') + 'B';
            if (abs >= 1.0e6) return (num / 1.0e6).toFixed(2).replace(/\.00$/,'') + 'M';
            if (abs >= 1.0e3) return (num / 1.0e3).toFixed(2).replace(/\.00$/,'') + 'K';
            return num.toString();
        }

        function displayPost(){

            const count = 10;
                fetch(`/get-posts?limit=${count}/`)
                .then(res => res.json())
                .then(data => 
                   { 
                    const addUrlInfo = `{{url ('product_info')}}`;
                    const addUrlCart = `{{url ('see_cart_product')}}`;
                    const container = document.getElementById('postContainer');
                    container.innerHTML = ``;
                    data.forEach(post => container.innerHTML += `

                    

                    <div class="col-lg-3 col-md-4 col-6 pb-1" >
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/${post.image}" alt="">
                            <div class="product-action">
                                 
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">${ post.product }</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh ${moneyFormat(post.price)}</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="${addUrlInfo}/${post.id}"><button style="border-radius: 8px;" data-product-id="${post.id}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="${addUrlInfo}/${post.id}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                    
                    
                    `);


            });

        }
        displayPost();


        document.getElementById('filterByNumber').addEventListener('change', 
            function () {
                const limit = this.value;
                
                console.log(limit);
                fetch(`/get-posts?limit=${limit}/`)
                .then(res => res.json())
                .then(data => 
                   { 
                    const addUrlInfo = `{{url ('product_info')}}`;
                    const addUrlCart = `{{url ('see_cart_product')}}`;
                    const container = document.getElementById('postContainer');
                    container.innerHTML = ``;
                    data.forEach(post => container.innerHTML += `

                    

                    <div class="col-lg-3 col-md-4 col-6 pb-1" >
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/${post.image}" alt="">
                            <div class="product-action">
                                 
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">${ post.product }</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh${moneyFormat(post.price)}</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="${addUrlInfo}/${post.id}"><button style="border-radius: 8px;" data-product-id="${post.id}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="${addUrlInfo}/${post.id}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                    
                    
                    `);


            });
            }
        )


        document.getElementById('filterByRate').addEventListener('change', 
            function () {
                const rate = this.value;
                const limit = 2;
                console.log(rate);
                fetch(`/get-posts-rate?rate=${rate}&limit=${limit}/`)
                .then(res => res.json())
                .then(data => 
                   { 
                    const addUrlInfo = `{{url ('product_info')}}`;
                    const addUrlCart = `{{url ('see_cart_product')}}`;
                    const container = document.getElementById('postContainer');
                    container.innerHTML = ``;
                    data.posts.forEach(post => container.innerHTML += `

                    

                    <div class="col-lg-3 col-md-4 col-6 pb-1" >
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/${post.image}" alt="">
                            <div class="product-action">
                                 
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">${ post.product }</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh${moneyFormat(post.price)}</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="${addUrlInfo}/${post.id}"><button style="border-radius: 8px;" data-product-id="${post.id}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="${addUrlInfo}/${post.id}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                    
                    
                    `);


            });
            }
        )


         document.querySelectorAll('.filterByPrice').addEventListener('change', 
            function () {
                const price = this.value;
                const limit = 3;
                console.log(price);
                fetch(`/get-posts-price?price=${price}&limit=${limit}/`)
                .then(res => res.json())
                .then(data => 
                   { 
                    const addUrlInfo = `{{url ('product_info')}}`;
                    const addUrlCart = `{{url ('see_cart_product')}}`;
                    const container = document.getElementById('postContainer');
                    container.innerHTML = ``;
                    data.posts.forEach(post => container.innerHTML += `

                    

                    <div class="col-lg-3 col-md-4 col-6 pb-1" >
                    <div class="product-item bd-bg bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid add-cart-btn" src="product_image/${post.image}" alt="">
                            <div class="product-action">
                             
                           
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="">${ post.product }</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Tsh${moneyFormat(post.price)}</h5>
                            </div>
                             <div class="d-flex align-items-center justify-content-center mb-1">
                           <a href="${addUrlInfo}/${post.id}"><button style="border-radius: 8px;" data-product-id="${post.id}" class="add-to-cart-btn btn align-items-center justify-content-center mb-1 btn-primary">BuyNow</button><a>
                        </div>
                           <a href="${addUrlInfo}/${post.id}"><h6 class="text-primary align-items-center justify-content-center mb-1  ">Product Details?</h6></a>

                        </div>
                    </div>
                </div>
                    
                    
                    `);


            });
            }
        )
    </script>


    <script>

     
        document.addEventListener('click', 
            function(e){
                if (e.target.classList.contains('add-to-cart-btn')){

                     let productId = e.target.dataset.productId;
                        fetch('/add-to-cart-btn', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            console.log(data.product_cart_id);
                            display_carts();
                        })
                        .catch(err => console.error(err));
                };
                })
               
     


     function display_carts(){
        const cart_list = document.getElementById('cart_list');

        fetch('/show_cart', {
            method: 'GET'
     })
    .then(res => res.json())
    .then(data => {

        console.log("work bro!!!!");

    }).catch(err => console.error(err));
         
     }

     display_carts();

    </script>


    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const cartrows = document.querySelectorAll('tr');
            const cartTotalElement = document.getElementById('carttotal');
            const subTotalElement = document.getElementById('subtotal');
            const subTotalElement2 = document.querySelectorAll('.subtotal2');


            function update_cart_total(){
                let total = 0;
                cartrows.forEach(row => {
                    const quantityInput = row.querySelector('.quantity-input');
                    const priceElement = row.querySelector('.price');
                    const itemTotalElement = row.querySelector('.item_total');

                    if (quantityInput && priceElement && itemTotalElement){
                        const price = parseFloat(priceElement.textContent);
                        const quantity = parseInt(quantityInput.value);
                        const subTotal = price * quantity;
                        itemTotalElement.textContent = subTotal.toFixed(2);

                        total += subTotal;

                    }
                });
                
                cartTotalElement.textContent = total.toFixed(2);
                subTotalElement.textContent = total.toFixed(2);
                subTotalElement2.textContent = total.toFixed(2);


            }

            document.querySelectorAll('.btn-plus').forEach(button => {
                button.addEventListener('click', () => {
                    const input = button.parentElement.parentElement.querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                    update_cart_total();
                });
            });

            document.querySelectorAll('.btn-minus').forEach(button => {
                button.addEventListener('click', () => {
                    const input = button.parentElement.parentElement.querySelector('.quantity-input');
                    const current = parseInt(input.value);
                    if (current > 1){
                    input.value = current - 1;
                    update_cart_total();
                    };
                });
            });

            update_cart_total();
        })



       

    </script>


    