<?php

use App\Http\Controllers\Api\DemoTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DemoTestInquiryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Demo Test Inquiries
Route::post('/demo/test', [DemoTestInquiryController::class, 'store']);

// Demo Test
Route::post('/demo/test/{demoTest:ref}/activate', [DemoTestController::class, 'activate']);
Route::post('/demo/test/{demoTest:ref}/deactivate', [DemoTestController::class, 'deactivate']);
