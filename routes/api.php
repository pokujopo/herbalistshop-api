<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AdminCategoryController;
use App\Http\Controllers\Api\AdminNotificationController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AdminNewsletterController;
use App\Http\Controllers\Api\AdminSettingController;
use App\Http\Controllers\Api\AdminCouponController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\ProfolioController;









Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
/*
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
});
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
*/

// Public
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/payments/webhook/{provider}', [PaymentWebhookController::class, 'handle']);
Route::post('/post_project', [ProfolioController::class, 'post_project']);
Route::get('/get_project', [ProfolioController::class, 'get_project']);
Route::get('/search', [ProfolioController::class, 'search']);
Route::post('/detect_view', [ProfolioController::class, 'detect_view']);
Route::get('count_view/{id}', [ProfolioController::class, 'count_view']);
Route::post('/detect_like', [ProfolioController::class, 'detect_like']);
Route::get('count_like/{id}', [ProfolioController::class, 'count_like']);


Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products/{product}/images', [ProductController::class, 'uploadImages']);
    Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage']);

    Route::get('/admin/orders', [AdminOrderController::class, 'index']);
    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show']);
    Route::put('/admin/orders/{order}', [AdminOrderController::class, 'update']);

    Route::post('/admin/categories', [AdminCategoryController::class, 'store']);
    Route::post('/admin/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy']);

    Route::get('/admin/dashboard/analytics', [AdminDashboardController::class, 'analytics']);

    Route::get('/admin/newsletters', [AdminNewsletterController::class, 'index']);

    Route::get('/admin/settings', [AdminSettingController::class, 'index']);
    Route::post('/admin/settings', [AdminSettingController::class, 'storeOrUpdate']);

    Route::get('/admin/coupons', [AdminCouponController::class, 'index']);
    Route::post('/admin/coupons', [AdminCouponController::class, 'store']);
    Route::post('/admin/coupons/{coupon}', [AdminCouponController::class, 'update']);
    Route::delete('/admin/coupons/{coupon}', [AdminCouponController::class, 'destroy']);

    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::get('/admin/users/{user}', [AdminUserController::class, 'show']);
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update']);

    Route::get('/admin/notifications', [AdminNotificationController::class, 'index']);
    Route::post('/admin/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead']);
    Route::post('/admin/notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);



// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AccountController::class, 'profile']);
    Route::put('/me', [AccountController::class, 'updateProfile']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{cartItem}', [CartController::class, 'update']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/checkout', [OrderController::class, 'store']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
    Route::get('/orders/{orderNumber}/track', [OrderController::class, 'track']);
    Route::post('/checkout/pay', [PaymentController::class, 'pay']);
    Route::get('/payments/{orderNumber}/status', [PaymentController::class, 'status']);
    Route::post('/payments/{orderNumber}/retry', [PaymentController::class, 'retry']);
});

Route::post('/yas/{refercode}', function ($refercode) {

    $all_customer = [
        "ABC823" => [
            "refer_code" => "ABC823",
            "customer_name" => "john doe",
            "invitor_number" => 30000,
        ],

        "ABC120" => [
            "refer_code" => "ABC120",
            "customer_name" => "jo de",
            "invitor_number" => 98000000000,
        ],

        "ABC999" => [
            "refer_code" => "ABC999",
            "customer_name" => "Test User",
            "invitor_number" => 2340000000,
        ],

        "ABC270" => [
            "refer_code" => "ABC270",
            "customer_name" => "Te User",
            "invitor_number" => 200000000000,
        ],
        "ABC83" => [
            "refer_code" => "ABC83",
            "customer_name" => "john doe",
            "invitor_number" => 30000,
        ],

        "ABC10" => [
            "refer_code" => "ABC10",
            "customer_name" => "jo de",
            "invitor_number" => 98000000000,
        ],

        "ABC99" => [
            "refer_code" => "ABC99",
            "customer_name" => "Test User",
            "invitor_number" => 2340000000,
        ],

        "ABC20" => [
            "refer_code" => "ABC20",
            "customer_name" => "Te User",
            "invitor_number" => 200000000000,
        ],

        "ABC12" => [
            "refer_code" => "ABC12",
            "customer_name" => "jo de",
            "invitor_number" => 98000000000,
        ],

        "ABC130" => [
            "refer_code" => "ABC130",
            "customer_name" => "Test User",
            "invitor_number" => 2340000000,
        ],

        "ABC278" => [
            "refer_code" => "ABC278",
            "customer_name" => "Te User",
            "invitor_number" => 200000000000,
        ],
        "ABC833" => [
            "refer_code" => "ABC833",
            "customer_name" => "john doe",
            "invitor_number" => 30000,
        ],
    ];

    if (!isset($all_customer[$refercode])) {
        return response()->json([
            "status" => 404,
            "message" => "Refercode not found",
        ], 404);
    }

    return response()->json([
        "status" => 200,
        "customer_all" => $all_customer[$refercode],
    ], 200);
});

/*
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\betmakinController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\ProfolioController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/make_payment', [betmakinController::class, 'makePayment']);

Route::post('/post_project', [ProfolioController::class, 'post_project']);
Route::get('/get_project', [ProfolioController::class, 'get_project']);
Route::get('/search', [ProfolioController::class, 'search']);
Route::post('/detect_view', [ProfolioController::class, 'detect_view']);
Route::get('count_view/{id}', [ProfolioController::class, 'count_view']);
Route::post('/detect_like', [ProfolioController::class, 'detect_like']);
Route::get('count_like/{id}', [ProfolioController::class, 'count_like']);

Route::get('posts', [homeController::class, 'posts_api']);
Route::get('/search_posts', [homeController::class, 'search_api']);

*/
