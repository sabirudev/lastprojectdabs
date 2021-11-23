<?php

use App\Http\Controllers\API\User;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/user')->group(function () {
    Route::get('check_data', [User::class, 'check_data']);
    Route::post('submit_data', [User::class, 'submit_data']);
    Route::post('submit_data_email', [User::class, 'submit_data_email']);
    Route::post('post_score', [User::class, 'post_score']);
});
