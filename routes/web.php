<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

//product
Route::post('/create_product', [AdminController::class,'addProduct']);
Route::get('/get_products', [AdminController::class,'getProducts']);
Route::post('/edit_product', [AdminController::class,'editProduct']);
Route::post('/delete_product', [AdminController::class,'deleteProduct']);
Route::post('/active_product', [AdminController::class,'activeProduct']);

//category
Route::post('/create_category', [AdminController::class,'addCategory']);
Route::get('/get_categories', [AdminController::class,'getCategories']);
Route::post('/edit_category', [AdminController::class,'editCategory']);
Route::post('/delete_category', [AdminController::class,'deleteCategory']);

//image
Route::post('/upload', [AdminController::class,'upload']);
Route::post('/delete_image', [AdminController::class,'deleteImage']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', function () {
    return view('welcome');
});


