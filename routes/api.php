<?php

use App\Http\Controllers\Api\InquiryController;
use Illuminate\Support\Facades\Route;

Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware('throttle:15,1');
