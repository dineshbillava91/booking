<?php

use App\Http\Controllers\Api\BookableController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Route::get('bookables', function(Request $request) {
//     return Bookable::all();
// });

// Route::get('bookables/{bookable}', function(Request $request, Bookable $bookable) {
//     //route model binding in routes file (can be done in controller)
//     return $bookable;
// });

// Route::get('bookables/{bookable}/{optional?}', function(Request $request, Bookable $bookable, $optional = null) {
//     return Bookable::find($bookable);
// });


//define separate route
// Route::get('bookables',  'Api\BookableController@index');
// Route::get('bookables/{bookable}',  'Api\BookableController@show');

// //another way
// Route::get('bookables',  [BookableController::class,'index']);



//use resource (defines index, create, store, show, edit, update, destroy routes)
// Route::resource('bookables',Api\BookableController::class);
//or
Route::apiResource('bookables','Api\BookableController')->only('index','show');

Route::prefix('bookables/{bookable}')->group(function () {
    Route::get('/availability','Api\BookableAvailability')->name('bookables.availability.show');
    Route::get('/reviews', 'Api\BookableReviewcontroller')->name('bookables.reviews.index');
    Route::get('/price','Api\BookablePriceController');
});

Route::get('booking-by-review/{reviewKey}','Api\BookingByReviewController')->name('booking.by-review.show');


Route::apiResource('reviews', 'Api\ReviewController')->only('show','store');

Route::post('checkout', 'Api\CheckoutController')->name('checkout');

