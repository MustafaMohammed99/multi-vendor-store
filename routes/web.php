<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\Front\StripePaymentController;
use App\Http\Controllers\Front\StripeWebhookController;
use App\Http\Controllers\Front\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductsController::class, 'index'])
    ->name('products.index');
Route::get('/products/{product:slug}', [ProductsController::class, 'show'])
    ->name('products.show');

Route::resource('cart', CartController::class);
Route::resource('wishlist', WishlistController::class);

Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store']);


Route::get('orders/{order}/pay', [StripePaymentController::class, 'create'])
    ->name('orders.payments.create');

Route::post('orders/{order}/stripe/paymeny-intent', [StripePaymentController::class, 'createPaymentIntent'])
    ->name('stripe.paymentIntent.create');

Route::get('orders/{order}/pay/stripe/callback', [StripePaymentController::class, 'confirm'])
    ->name('stripe.return');

// crisp-outdo-goood-appeal
Route::post('stripe/webhook', [StripeWebhookController::class, 'handle']);
// طبعا بينفعش يجيني ريكوست من السترايب بوست ومش مبعوت التوكن
// عشان تنحل القصة هادي بروح على ميدلوير تاع التوكن بحط اكسبت هادا الراوت
// └─$ stripe trigger payment_intent.created عشان اعمل تست بنزل cli stripe amd  وبكتب هادا الكوماند


Route::post('currency', [CurrencyConverterController::class, 'store'])
    ->name('currency.store');



require __DIR__ . '/dashboard.php';
