<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieScheduleController;
use App\Http\Controllers\Api\StudioController;
use App\Http\Controllers\Api\BackOfficeController;
use App\Http\Controllers\Api\OrderController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// tanpa middleware
Route::get('/v1/movies', [MovieScheduleController::class, 'listMovies']);

Route::post('/v1/auth/register', [AuthController::class, 'createUser']);
Route::post('/v1/auth/login', [AuthController::class, 'loginUser']);
Route::get('/v1/auth/logout', [AuthController::class, 'logoutUser']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/v1/backoffice/tags', [BackofficeController::class, 'listTags']);
    
    Route::get('/v1/studio', [StudioController::class, 'index']);
    Route::post('/v1/studio/insert', [StudioController::class, 'store']);
    Route::put('/v1/studio/update/{studio}', [StudioController::class, 'update']);
    Route::delete('/v1/studio/delete/{id}', [StudioController::class, 'destroy']);
    
    Route::get('/v1/backoffice/movies', [BackofficeController::class, 'listMovies']);
    Route::post('/v1/backoffice/movies/schedule', [BackofficeController::class, 'storeMovieSchedule']);
    Route::post('/v1/backoffice/movies/{movie}', [BackofficeController::class, 'updateMovie']);

    Route::post('/v1/order/checkout/preview', [OrderController::class, 'orderPreview']);
    Route::post('/v1/order/checkout', [OrderController::class, 'orderCheckout']);
    
});
