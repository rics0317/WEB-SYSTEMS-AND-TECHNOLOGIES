<?php


use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Socialite2Controller;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\EnsureEmailIsVerifiedMiddleware;
use App\Http\Middleware\UserMiddleware; 
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

    Route::controller(SocialiteController::class)->group(function() {
    Route::get('auth/redirection/{provider}', 'authProviderRedirect')->name('auth.redirection');

    Route::get('auth/{provider}/callback', 'socialAuthentication')->name('auth.callback');
});

    Route::controller(Socialite2Controller::class)->group(function() {
    Route::get('auth/google', 'googleLogin')->name('auth.google');
    Route::get('auth/google-callback', 'googleAuthentication')->name('auth.google-callback');
});

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Route to show the email verification notice
Route::get('/email/verify', [VerificationController::class, 'show'])->middleware(['auth'])->name('verification.notice');

// Route to handle email verification
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

// Route to resend the email verification notification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['resent' => true]);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route to check email verification status
Route::get('/email/verify/status', function (Request $request) {
    return response()->json(['verified' => $request->user()->hasVerifiedEmail()]);
})->middleware(['auth'])->name('verification.status');

// Login Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


    Route::get('/', [UserHomeController::class, 'index']);


    Auth::routes();
    
    

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('register-details', [RegisterController::class, 'registerDetails'])->name('register.details');

    Route::get('/users/home', [UserHomeController::class, 'index'])->name('users.home');
    Route::get('/users/partials/header', [UserHomeController::class, 'showCart'])->name('users.partials.header');

    //PAYMENTS METHODS
    Route::post('/save-transaction', [OrderController::class, 'saveTransaction']);
    Route::get('/users/order-confirmation/{orderId}', [OrderController::class, 'orderConfirmation']);
    Route::get('/users/order-confirmation/{orderId}', [OrderController::class, 'orderConfirmation'])->name('users.order.confirmation');
  


    Route::middleware([UserMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/users/profile', [UserHomeController::class, 'profile'])->name('profile');
    Route::put('/users/profile', [UserHomeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/users/profile/update', [UserHomeController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/users/profile/change-password', [UserHomeController::class, 'changePassword'])->name('profile.change');
    Route::put('/users/profile/change-password', [UserHomeController::class, 'updatePassword'])->name('profile.change.password');
    Route::get('/users/profile/edit', [UserHomeController::class, 'editProfile'])->name('profile.edit');
    Route::get('/profile/edit-phone', [UserHomeController::class, 'editPhone'])->name('profile.edit.phone');
Route::put('/profile/update-phone', [UserHomeController::class, 'updatePhone'])->name('profile.update.phone');
    Route::get('/users/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.get');
    Route::post('/users/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::get('/users/notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/users/notifications/markAsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/users/notifications/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/users/notifications/deleteSelected', [NotificationController::class, 'deleteSelected'])->name('notifications.deleteSelected');
    Route::get('/users/notifications/view-all', [NotificationController::class, 'viewAll'])->name('notifications.viewAll');
    Route::post('/users/profile/create-address', [UserHomeController::class, 'createAddress'])->name('profile.createAddress');
    Route::post('/users/profile/set-default-address', [UserHomeController::class, 'setDefaultAddress'])->name('profile.setDefaultAddress');
    Route::get('/users/my-purchases', [UserHomeController::class, 'myPurchases'])->name('my-purchases');
    Route::post('/rate-order', [UserHomeController::class, 'rateOrder'])->name('rate.order');
    Route::get('/buy-again/{orderId}', [UserHomeController::class, 'buyAgain'])->name('buy.again');
    Route::get('/product/{id}', [UserHomeController::class, 'showProduct'])->name('product.details');
    Route::post('/users/cart/update/{cartItemId}', [CartController::class, 'updateCartItemQuantity'])->name('users.cart.update');
    Route::post('/profile/deleteAddress', [UserHomeController::class, 'deleteAddress'])->name('profile.deleteAddress');
    Route::post('/profile/getAddress', [UserHomeController::class, 'getAddress'])->name('profile.getAddress');
    Route::post('/profile/updateAddress', [UserHomeController::class, 'updateAddress'])->name('profile.updateAddress');
});

    Route::get('/seller-registration', function () {
        return view('users.seller-registration');
    })->name('seller.registration');

    Route::get('/seller/createproducts', function () {
        return view('users.seller-createproducts');
    })->name('seller.createproducts');

    Route::get('/admin-dashboard/form', function () {
        return view('admin.admin-dashboard-form');
    })->name('admin.products.registration.form');

    
    
    
    Route::get('users/product/{id}', [UserHomeController::class, 'getProductDetails']);
    Route::get('users/product/{id}', [UserHomeController::class, 'showProduct'])->name('product.show');
    Route::get('users/category/{id}', [UserHomeController::class, 'showCategory'])->name('category.show');
    Route::get('/search-products', [App\Http\Controllers\Seller\ProductController::class, 'search'])->name('products.search');

    Route::middleware([PreventBackHistory::class])->group(function () {
    Route::get('users/cart', [CartController::class, 'index'])->name('users.cart');
    Route::post('users/cart/add/{product}', [CartController::class, 'addToCart'])->name('users.cart.add');
    Route::delete('users/cart/remove/{cartItem}', [CartController::class, 'removeFromCart'])->name('users.cart.remove');
    Route::get('users/cart/checkout', [CartController::class, 'checkout'])->name('users.cart.checkout');
    Route::post('/cart/add/{productId}', [UserHomeController::class, 'addToCart'])->name('cart.add');
});




    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/seller/search-subcategories', [ProductController::class, 'searchSubCategories'])->name('seller.search-subcategories');
    Route::get('/seller/search-items', [ProductController::class, 'searchItems'])->name('seller.search-items');
    Route::get('/admin/products/add-product-step1', [ProductController::class, 'addProductStep1'])->name('admin.products.add-product-step1');
    Route::get('/admin/products/add-product-step2', [ProductController::class, 'addProductStep2'])->name('admin.products.add-product-step2');
    Route::post('/seller/update-product/{id}', [ProductController::class, 'updateProduct'])->name('seller.update-product');
    Route::delete('/seller/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('seller.delete-product');
    Route::delete('/seller/delete-product-image/{id}', [ProductController::class, 'deleteProductImage'])->name('seller.delete-product-image');
    Route::post('/add-brand', [ProductController::class, 'addBrand'])->name('seller.add-brand');
    Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('seller.update-product');
    Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('seller.delete-product');
    Route::get('/admin/products/edit-product/{id}', [ProductController::class, 'editProduct'])->name('admin.products.edit-product');
    Route::delete('/seller/delete-image/{imageId}', [ProductController::class, 'deleteImage'])->name('seller.delete-image');
    Route::get('/admin/dashboard', [AdminController::class, 'dashBoard'])->name('admin.dashboard');
    Route::get('/admin/myproducts', [ProductController::class, 'myProducts'])->name('admin.myproducts');
    Route::get('/seller/products/{id}/edit', [ProductController::class, 'editProduct'])->name('seller.edit-product');
    Route::put('/seller/products/{id}', [ProductController::class, 'updateProduct'])->name('seller.update-product');
    Route::delete('/seller/products/{id}', [ProductController::class, 'deleteProduct'])->name('seller.delete-product');
    Route::post('/seller/store-product', [ProductController::class, 'storeProduct'])->name('seller.store-product');
    Route::get('/search-categories', [ProductController::class, 'searchCategories'])->name('seller.search-categories');
    Route::get('/admin/get-notification-counts', [AdminController::class, 'getNotificationCounts'])->name('admin.getNotificationCounts');
    Route::post('/admin/variation-options', [ProductController::class, 'storeVariationOptions'])->name('admin.store-variation-options');
});
    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/admin/profile/change-prof', [ProfileController::class, 'profile'])->name('admin.profile.change-prof');
    Route::post('/admin/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/admin/profile', [ProfileController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile/update/{user}', [ProfileController::class, 'update'])->name('admin.update');
    Route::get('admin/profile/change-pass', [ProfileController::class, 'changePassword'])->name('admin.changePassword');
    Route::put('admin/profile/change-pass', [ProfileController::class, 'updatePassword'])->name('admin.updatePassword');
    Route::get('/admin/change-password', [ProfileController::class, 'changePassword'])->name('admin.changePassword');
    Route::post('/admin/update-password', [ProfileController::class, 'updatePassword'])->name('admin.updatePassword');
});

    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::put('/admin/products/categories/{id}/update', [CategoryController::class, 'update'])->name('admin.products.categories.update');
    Route::get('/admin/products/categories/add', [CategoryController::class, 'showAddCategoryForm'])->name('admin.products.categories.add');
    Route::post('/admin/products/categories/add', [CategoryController::class, 'addCategory'])->name('admin.products.categories.add');
    Route::post('/admin/products/subcategories/add', [CategoryController::class, 'addSubCategory'])->name('admin.products.subcategories.add');
    Route::post('/admin/products/items/add', [CategoryController::class, 'addItem'])->name('admin.products.items.add');
    Route::get('products/categories/edit/{id}', [CategoryController::class, 'editCategory'])->name('admin.products.categories.edit');
    Route::put('products/categories/update/{id}', [CategoryController::class, 'update'])->name('admin.products.categories.update');
    Route::delete('products/categories/delete/{id}', [CategoryController::class, 'delete'])->name('admin.products.categories.delete');
    Route::put('/admin/products/categories/{id}', [CategoryController::class, 'update'])->name('admin.products.categories.update');
    Route::get('/admin/products/categories/add', [CategoryController::class, 'showAddCategoryForm'])->name('admin.products.categories.add');
    Route::get('/admin/products/subcategories/add', [CategoryController::class, 'showAddSubCategoryForm'])->name('admin.products.subcategories.add');
    Route::post('/admin/products/categories/add', [CategoryController::class, 'addCategory'])->name('admin.products.categories.add');
    Route::post('/admin/products/subcategories/add', [CategoryController::class, 'addSubCategory'])->name('admin.products.subcategories.add');
    Route::delete('/admin/products/subcategories/{id}', [CategoryController::class, 'deleteSubCategory'])->name('admin.products.subcategories.delete');
    Route::put('/admin/products/subcategories/{id}', [CategoryController::class, 'updateSubCategory'])->name('admin.products.subcategories.update');
    Route::get('/admin/products/itemcategories/add', [CategoryController::class, 'showAddItemCategoryForm'])->name('admin.products.itemcategories.add');
    Route::post('/admin/products/itemcategories/add', [CategoryController::class, 'addItemCategory'])->name('admin.products.itemcategories.add');
    Route::delete('itemcategories/{id}', [CategoryController::class, 'deleteItemCategory'])->name('admin.products.itemcategories.delete');
    Route::put('/admin/products/itemcategories/{id}', [CategoryController::class, 'updateItemCategory'])->name('admin.products.itemcategories.update');
    Route::post('products/categories/delete', [CategoryController::class, 'delete'])->name('products.categories.delete');

});

    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/admin/inventory/stocks/add', [StockController::class, 'index'])->name('admin.inventory.stocks.add');
    
Route::get('/admin/inventory/stocks/variations/{productId}', [StockController::class, 'getVariationOptions']);
Route::get('/admin/inventory/stocks/sizes/{variationOptionId}', [StockController::class, 'getVariationSizes']);
Route::get('/admin/inventory/stocks/variations/{variationOptionId}/stock', [StockController::class, 'getVariationOptionStock']);
Route::get('/admin/inventory/stocks/sizes/{variationOptionSizeId}/stock', [StockController::class, 'getVariationOptionSizeStock'])->name('admin.inventory.stocks.size.stock');
Route::get('/admin/inventory/stocks/variations/{variationOptionId}/sizes', [StockController::class, 'getVariationOptionSizes'])->name('admin.inventory.stocks.variation.sizes');
    Route::post('/admin/inventory/stocks/add', [StockController::class, 'store'])->name('admin.inventory.stocks.store');
    Route::get('/admin/inventory/stocks/history', [StockController::class, 'history'])->name('admin.inventory.history.add');
});

    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::post('/admin/products/store', [BrandController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/add-newbrand', [BrandController::class, 'showAddBrandForm'])->name('admin.products.add-newbrand');
    Route::post('/admin/products/add-newbrand', [BrandController::class, 'store'])->name('admin.products.store-brand');
    Route::post('/admin/products/get-items', [BrandController::class, 'getItems'])->name('admin.products.get-items');
    Route::delete('/admin/products/brands/{id}', [BrandController::class, 'destroy'])->name('admin.products.delete-brand');
    Route::put('/admin/products/brands/{id}', [BrandController::class, 'update'])->name('admin.products.update-brand');
});

    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/admin/manage/user-management', [UserController::class, 'index'])->name('admin.manage.user-management');
    Route::get('/admin/manage/user-management', [UserController::class, 'showUserManagement'])->name('admin.manage.user-management');
    Route::post('/admin/manage/user-management', [UserController::class, 'store'])->name('admin.manage.store-user');
    Route::put('/admin/manage/user-management/{id}', [UserController::class, 'update'])->name('admin.manage.update-user');
    Route::delete('/admin/manage/user-management/{id}', [UserController::class, 'destroy'])->name('admin.manage.delete-user');
    Route::get('/admin/manage/user-management/{id}/view', [UserController::class, 'view'])->name('admin.manage.view-user');

});
    Route::middleware([AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/admin/orders/all', [OrdersController::class, 'index'])->name('admin.orders.all');
    Route::get('/admin/orders/view/{id}', [OrdersController::class, 'view'])->name('admin.orders.view');
    Route::get('/admin/orders/edit/{id}', [OrdersController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/admin/orders/update/{id}', [OrdersController::class, 'update'])->name('admin.orders.update');
    Route::delete('/admin/orders/delete/{id}', [OrdersController::class, 'destroy'])->name('admin.orders.delete');
    Route::get('admin/orders/{id}/edit', [OrdersController::class, 'edit'])->name('admin.orders.edit');
    Route::put('admin/orders/{id}', [OrdersController::class, 'update'])->name('admin.orders.update');
    Route::get('/admin/orders/history', [OrdersController::class, 'orderHistory'])->name('admin.orders.history');
    Route::resource('banners', BannerController::class);


    });