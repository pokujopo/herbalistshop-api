<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\azampayController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\betmakinController;
use App\Http\Controllers\ZenopayCallbackController;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\Post;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', [homeController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/index', [homeController::class, 'index'])->name('index');
Route::get('/see_cart_product', [homeController::class, 'see_cart_product']);

Route::get('/shop', [homeController::class, 'shop']);
Route::post('/filter_product', [homeController::class, 'filter_product']);
Route::get('/filter_category', [homeController::class, 'filter_category']);
Route::get('/get-posts-category',[homeController::class, 'get_post_category'] );
Route::get('get-posts', [homeController::class, 'get_post']);
Route::get('/get-posts-rate', [homeController::class, 'get_post_rate']);
Route::get('/get-posts-price', [homeController::class, 'get_product_price']);
Route::get('/see_virutubisho_category', [homeController::class, 'virutubisho_category']);
Route::get('/shop_category/{category}', [homeController::class, 'shop_category']);

Route::get('/search', [homeController::class, 'search']);


Route::get('/product_info/{id}',[homeController::class, 'product_info']);
Route::get('/get_comments/{id}',[homeController::class, 'get_comments']);
Route::post('/post_comment', [homeController::class, 'post_comment']);

Route::get('/betmakin', [betmakinController::class, 'betmakin']);
Route::post('/zenopay/callback', [ZenopayCallbackController::class, 'handle']);


Route::post('/add-to-cart-btn', [homeController::class, 'add_cart_btn']);
Route::get('/show_cart', [homeController::class, 'show_cart']);
Route::get('/delete_cart_product/{id}', [homeController::class, 'delete_cart']);
Route::get('/delete_cart/{id}', [homeController::class, 'delete_grouped_cart']);

Route::get('/proceed_checkout', [homeController::class, 'checkout_page']);
Route::get('/azampay_page', [azampayController::class, 'azampay']);
Route::get('/azamapay/callback', [azampayController::class, 'callback']);

#use App\Http\Controllers\PaymentController;

Route::post('/azampay/pay', [PaymentController::class, 'initiatePayment']);
Route::get('/azampay/callback', [PaymentController::class, 'handleCallback']);

Route::get('/test_page', function(){
    return view('test');
});

Route::get('/examplepage', function(){
    return view('examplepage');
});


Route::middleware('auth', 'admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/admin_page', [adminController::class, 'admin_page']);
    Route::get('/post_product_page', [adminController::class, 'post_product_page']);
    Route::post('/post_product', [adminController::class, 'post_product']);
    Route::get('/show_product_page', [adminController::class, 'show_product' ]);
    Route::get('/delete_product/{id}', [adminController::class,'delete_product']);
    Route::get('/update_product_page/{id}', [adminController::class, 'update_page']);
    Route::post('/update_product/{id}', [adminController::class, 'update_product']);
    Route::post('/change_banner_image', [adminController::class, 'change_banner']);

    
});

require __DIR__.'/auth.php';
