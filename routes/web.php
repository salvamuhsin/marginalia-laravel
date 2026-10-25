<?php
use App\Http\Controllers\{AuthController, CartController, CheckoutController, PageController, ProductController, WishlistController};
use App\Http\Controllers\Admin;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

//Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSend'])->name('contact.send')->middleware('throttle:5,1');
Route::post('/subscribe', [PageController::class, 'subscribe'])->name('subscribe')->middleware('throttle:5,1');

Route::get('/books', [ProductController::class, 'index'])->defaults('type', 'book')->name('products.index');
Route::get('/accessories', [ProductController::class, 'index'])->defaults('type', 'accessory')->name('accessories.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

//Guests only: login / register 
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
});

//Logged-in users only: checkout, orders, account 
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{order}/paid', [CheckoutController::class, 'paid'])->name('checkout.paid');
    Route::get('/checkout/{order}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/order/{order}', [CheckoutController::class, 'done'])->name('checkout.done');
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//Admin only
Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except(['show', 'create']);
    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::get('inbox', [Admin\InboxController::class, 'index'])->name('inbox');
    Route::delete('inbox/messages/{message}', [Admin\InboxController::class, 'destroy'])->name('inbox.delete');
});
