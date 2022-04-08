<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FashionApiController;

/*

register
login

adding cloth suggestion
    //
        title
        
        
        keyword > Birthday, funeral, Thanksgiving, Date, Dinner 
       
           



suggesstion of cloth  should handle default search
    gender
    age
    wears
    event
detail suggesstion

|
*/

Route::post('register', [AuthApiController::class, 'createUserAccount']);
Route::post('login', [AuthApiController::class, 'loginUser']);

Route::post('fashion', [FashionApiController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
