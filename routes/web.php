<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;

// user
Route::post('/user/upload', [CategoryController::class, 'upload'])->name('user.upload');
Route::post('/user/cancle', [CategoryController::class, 'cancle'])->name('user.cancle');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::post('/user/list', [UserController::class, 'list'])->name('user.list');
Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::post('/user/destory/{id}', [UserController::class, 'destroy'])->name('user.destroy');

// Category
Route::post('/category/upload', [CategoryController::class, 'upload'])->name('category.upload');
Route::post('/category/cancle', [CategoryController::class, 'cancle'])->name('category.cancle');
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category/list', [CategoryController::class, 'list'])->name('category.list');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::post('/category/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/update', [CategoryController::class, 'update'])->name('category.update');
Route::post('/category/destory', [CategoryController::class, 'destroy'])->name('category.destroy');

// brand
Route::get("/brand",[BrandController::class,'index'])->name("brand.index");
Route::post("/brand/list",[BrandController::class,'list'])->name("brand.list");
Route::post("/brand/store",[BrandController::class,'store'])->name("brand.store");
Route::post("/brand/edit",[BrandController::class,'edit'])->name("brand.edit");
Route::post("/brand/update",[BrandController::class,'update'])->name("brand.update");
Route::post("/brand/destroy",[BrandController::class,'destroy'])->name("brand.destroy");

// color

Route::get("/color",[ColorController::class,'index'])->name("color.index");
Route::post("/color/list",[ColorController::class,'list'])->name("color.list");
Route::post("/color/store",[ColorController::class,'store'])->name("color.store");
Route::post("/color/edit",[ColorController::class,'edit'])->name("color.edit");
Route::post("/color/update",[ColorController::class,'update'])->name("color.update");
Route::post("/color/destroy",[ColorController::class,'destroy'])->name("color.destroy");