<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/decode', [\App\Http\Controllers\UrlMappingController::class, 'decode']);
Route::post('/encode', [\App\Http\Controllers\UrlMappingController::class, 'encode']);
