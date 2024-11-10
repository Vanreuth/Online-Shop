<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\DashboardMiddleware;

// Auth routes
Route::prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('auth.index');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');

    Route::middleware(AuthMiddleware::class)->group(function () {
        // Logout
        // User profile routes
        Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.editProfile');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
        Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Dashboard role
        Route::middleware(DashboardMiddleware::class)->group(function () {
            // Dashboard route
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

            // User routes
            Route::post('/user/upload', [CategoryController::class, 'upload'])->name('user.upload');
            Route::post('/user/cancel', [CategoryController::class, 'cancel'])->name('user.cancel'); // Corrected 'cancle'
            Route::get('/user', [UserController::class, 'index'])->name('user.index');
            Route::post('/user/list', [UserController::class, 'list'])->name('user.list');
            Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
            Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
            Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
            Route::post('/user/destroy', [UserController::class, 'destroy'])->name('user.destroy'); // Corrected 'destory'
        });

        // Category routes
        Route::post('/category/upload', [CategoryController::class, 'upload'])->name('category.upload');
        Route::post('/category/cancel', [CategoryController::class, 'cancel'])->name('category.cancel'); // Corrected 'cancle'
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::post('/category/list', [CategoryController::class, 'list'])->name('category.list');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
        Route::post('/category/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category/update', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/category/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');

        // Brand routes
        Route::get("/brand", [BrandController::class, 'index'])->name("brand.index");
        Route::post("/brand/list", [BrandController::class, 'list'])->name("brand.list");
        Route::post("/brand/store", [BrandController::class, 'store'])->name("brand.store");
        Route::post("/brand/edit", [BrandController::class, 'edit'])->name("brand.edit");
        Route::post("/brand/update", [BrandController::class, 'update'])->name("brand.update");
        Route::post("/brand/destroy", [BrandController::class, 'destroy'])->name("brand.destroy");

        // Color routes
        Route::get("/color", [ColorController::class, 'index'])->name("color.index");
        Route::post("/color/list", [ColorController::class, 'list'])->name("color.list");
        Route::post("/color/store", [ColorController::class, 'store'])->name("color.store");
        Route::post("/color/edit", [ColorController::class, 'edit'])->name("color.edit");
        Route::post("/color/update", [ColorController::class, 'update'])->name("color.update");
        Route::post("/color/destroy", [ColorController::class, 'destroy'])->name("color.destroy");

        // Produxt routes
        Route::post("/product/upload", [ProductController::class, 'upload'])->name('product.upload');
        Route::post("/product/cancel", [ProductController::class, 'cancel'])->name('product.cancel'); // Corrected 'cancle'
        Route::get("/product", [ProductController::class, 'index'])->name("product.index");
        Route::post("/product/list", [ProductController::class, 'list'])->name("product.list");
        Route::post("/product/store", [ProductController::class, 'store'])->name("product.store");
        Route::post("/product/edit", [ProductController::class, 'edit'])->name("product.edit");
        Route::post("/product/update", [ProductController::class, 'update'])->name("product.update");
        Route::post("/product/destroy", [ProductController::class, 'destroy'])->name("product.destroy");
        Route::post("/product/date", [ProductController::class, 'data'])->name("product.data"); 


    });
});
