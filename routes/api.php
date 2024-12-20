<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\productController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\API\FrontEndController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\OrderController;

Route::group(['prefix' => 'v1'],function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('getUsers', [AuthController::class, 'userdetails']);
Route::get('user_search/{key}', [AuthController::class, 'search']);

Route::get('getCategory', [FrontEndController::class, 'category']);
Route::get('fetchCategory/{merchant}', [FrontEndController::class, 'userCategory']);
Route::get('getproducts/{merchant}', [FrontEndController::class, 'userproduct']);
Route::get('fetchproducts/{slug}', [FrontEndController::class, 'product']);
Route::get('collectionproducts/{slug}/{merchant}', [FrontEndController::class, 'fetch_product']);
Route::get('fetchFlashSale/{merchant}', [FrontEndController::class, 'flash_sale']);
Route::get('viewproductdetails/{merchant}/{product_merchant}/{product_id}', [FrontEndController::class, 'viewproduct']);

Route::post('add-to-cart', [CartController::class, 'addtocart']);
Route::get('cart', [CartController::class, 'viewcart']);
Route::put('cart-updateQuantity/{cart_id}/{scope}', [CartController::class, 'updatequantity']);
Route::delete('delete-cartitem/{cart_id}', [CartController::class, 'deletecartitem']);

Route::post('validate-order', [CheckoutController::class, 'validateorder']);
Route::post('place-order', [CheckoutController::class, 'placeorder']);

Route::get('products', [productController::class, 'index']);
Route::get('search/{key}', [productController::class, 'search']);
Route::get('user-search/{merchant}/{key}', [productController::class, 'usersearch']);


Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::get('/checkingAuthenticated', function(){
        return response()->json(['messge'=>'You are in', 'status'=>200],200);
    });
    
    //category
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::get('view-category', [CategoryController::class, 'index']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::delete('delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::get('all-category', [CategoryController::class, 'allcategory']);
    Route::post('updateCategory/{id}', [CategoryController::class, 'update']);

    //orders
    Route::get('admin/orders', [OrderController::class, 'index']);


    //product
    Route::post('store-product', [productController::class, 'store']);
    Route::get('view-product', [productController::class, 'index']);
    Route::get('edit-product/{id}', [productController::class, 'edit']);
    Route::post('update-product/{id}', [productController::class, 'update']);
    Route::delete('delete-product/{id}', [productController::class, 'destroy']);

    //product image
    Route::get('product-images/{product_id}', [ProductImageController::class, 'index']);
    Route::post('store-product-images/{id}', [ProductImageController::class, 'store']);


}); 

Route::middleware(['auth:sanctum'])->group(function () {
   
    Route::post('logout', [AuthController::class, 'logout']);

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');