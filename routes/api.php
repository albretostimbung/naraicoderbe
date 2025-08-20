<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\EventRegistrationController;

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

Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::get('settings', [SettingController::class, 'index']);
Route::get('partners', [PartnerController::class, 'index']);
Route::get('testimonials/featured', [TestimonialController::class, 'featured']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Event Routes
    Route::apiResource('events', EventController::class);
    Route::get('events/{event}/registrations', [EventController::class, 'registrations']);
    
    // Event Registration Routes
    Route::apiResource('event-registrations', EventRegistrationController::class);
    
    // Partner Routes
    Route::apiResource('partners', PartnerController::class)->except(['index']);
    
    // Setting Routes
    Route::apiResource('settings', SettingController::class)->except(['index']);
    
    // Testimonial Routes
    Route::apiResource('testimonials', TestimonialController::class);
});
