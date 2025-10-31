<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactusController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\FooterImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\WhyChooseUsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin')->controller(AdminAuthController::class)->group(function () {
    Route::post('/login',  'login');
    Route::post('/logout',  'logout')->middleware('auth:sanctum');
});

Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/',  'index');

    Route::post('/',  'store')->middleware('auth:sanctum');

    Route::get('/{id}',  'show');
    Route::get('sub-categories/{id}',  'showSubCategory');

    Route::get('/product-lines/{id}',  'showProductLine');

    Route::post('/product-lines/{id}/subcategories',  'addSubCategory')->middleware('auth:sanctum');

    Route::put('/sub-categories/{id}',  'editSubCategory');
    Route::delete('/sub-categories/{id}',  'deleteSubCategory');

    Route::post('/product-lines/{id}',  'editProductLine');
    Route::delete('/product-lines/{id}',  'deleteProductLine');

    Route::get('/product-lines/{categoryId}/subcategories',  'getSubcategories');
    Route::post('/{categoryId}/product-lines',  'addProductLine');
    Route::get('/{categoryId}/product-lines',  'getProductLines');
});

Route::prefix('/products')->controller(ProductController::class)->group(function () {
    Route::post('/{id}', 'update')->middleware('auth:sanctum');
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store')->middleware('auth:sanctum');
    Route::delete('/{id}', 'destroy')->middleware('auth:sanctum');
});

Route::prefix('/product-images')->controller(ProductImagesController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/',  'store');
    Route::delete('/{id}',  'destroy');
    Route::post('/reorder',  'reorder');
});

Route::prefix('/footer-images')->controller(FooterImageController::class)->group(function () {
    Route::get('/',  'index');
    Route::post('/',  'store')->middleware('auth:sanctum');
    Route::delete('/{id}',  'destroy')->middleware('auth:sanctum');
});

Route::prefix('/contactus')->controller(ContactusController::class)->group(function () {
    Route::get('/',  'index')->middleware('auth:sanctum');
    Route::post('/',  'store');
});

Route::prefix('/whychooseus')->controller(WhyChooseUsController::class)->group(
    function () {
        Route::get('/',  'index');
        Route::post('/',  'store')->middleware('auth:sanctum');
        Route::delete('/{id}',  'destroy')->middleware('auth:sanctum');
    }
);
Route::apiResource('credentials', CredentialController::class);
