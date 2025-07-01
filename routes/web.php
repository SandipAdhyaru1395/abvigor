<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Admin\AuthController as AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\BrandProductController as AdminBrandProductController;
use App\Http\Controllers\Admin\CatalogProductController as AdminCatalogProductController;
use App\Http\Controllers\Admin\BrandCategoryController as AdminBrandCategoryController;
use App\Http\Controllers\Admin\CatalogCategoryController as AdminCatalogCategoryController;

Route::get('/', function () {
    return view('front.login');
})->name('get.login');

Route::get('/login', function () {
    return view('front.login');
});

Route::get('/register', function () {
    return view('front.register');
})->name('get.register');

Route::post('/register', [UserController::class, 'register'])->name('post.register');
Route::post('/login', [UserController::class, 'login'])->name('post.login');

Route::group(['middleware' => ['auth.user']], function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('get.dashboard');

    Route::get('/orders', [OrderController::class, 'list'])->name('order.list');
    Route::get('/orders/get/{id}', [OrderController::class, 'get'])->name('order.get');
    Route::get('/orders/add', [OrderController::class, 'add'])->name('order.add');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('order.store');

    Route::get('/products', [ProductController::class, 'list'])->name('product.list');

    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile.get');

    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminController::class, 'index'])->name('get.login');

    Route::post('/login', [AdminController::class, 'login'])->name('post.login');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::get('/orders/list', [AdminOrderController::class, 'list'])->name('order.list');

        Route::get('/orders', [AdminOrderController::class, 'getOrders'])->name('get.orders');

        Route::post('/orders/delete', [AdminOrderController::class, 'deleteMultipleOrders'])->name('orders.delete');

        Route::post('/orders/products/delete', [AdminOrderController::class, 'deleteMultipleProducts'])->name('order.products.delete');

        Route::get('/orders/edit/{id}', [AdminOrderController::class, 'edit'])->name('order.edit');

        Route::post('/orders/product/create', [AdminOrderController::class, 'createOrderProduct'])->name('order.product.create');

        Route::get('/orders/product/edit/{id}', [AdminOrderController::class, 'editProduct'])->name('order.product.edit');

        Route::get('/orders/product/remove/{orderProductId}', [AdminOrderController::class, 'removeOrderProduct'])->name('order.product.remove');

        Route::post('/orders/product/add', [AdminOrderController::class, 'addOrderProduct'])->name('order.product.add');

        Route::post('/orders/product/update', [AdminOrderController::class, 'updateOrderProduct'])->name('order.product.update');

        Route::get('/orders/product/list', [AdminOrderController::class, 'getOrderProducts'])->name('order.product.list');

        Route::post('/orders/update', [AdminOrderController::class, 'update'])->name('order.update');

        Route::get('/orders/product/update/quantity/{orderProductId}', [AdminOrderController::class, 'updteOrderProductQty'])->name('order.product.updateQty');

        Route::get('/users/list', [AdminUserController::class, 'list'])->name('user.list');

        Route::get('/orders/add', [AdminOrderController::class, 'add'])->name('order.add');

        Route::post('/orders/store', [AdminOrderController::class, 'store'])->name('order.store');

        // Route::get('/products', [AdminProductController::class, 'getProducts'])->name('get.products');

        Route::get('/users', [AdminUserController::class, 'getUsers'])->name('get.users');

        Route::post('/users/delete', [AdminUserController::class, 'deleteMultiple'])->name('users.delete');

        Route::post('/users/restore', [AdminUserController::class, 'restoreMultiple'])->name('users.restore');

        Route::post('/users/deactivate', [AdminUserController::class, 'deactivateMultiple'])->name('users.deactivate');

        Route::post('/users/activate', [AdminUserController::class, 'activateMultiple'])->name('users.activate');

        Route::get('/users/add', [AdminUserController::class, 'add'])->name('user.add');

        Route::post('/users/store', [AdminUserController::class, 'store'])->name('user.store');

        Route::post('/users/update', [AdminUserController::class, 'update'])->name('user.update');

        Route::get('/users/view/{userId}', [AdminUserController::class, 'view'])->name('user.view');

        Route::get('/users/edit/{userId}', [AdminUserController::class, 'edit'])->name('user.edit');

        Route::get('/users/delete/{userId}', [AdminUserController::class, 'delete'])->name('user.delete');

        Route::get('/brands/categories/list', [AdminBrandCategoryController::class, 'list'])->name('brand.category.list');

        Route::get('/brands/categories', [AdminBrandCategoryController::class, 'getBrandCategories'])->name('get.brand.categories');

        Route::get('/brands/categories/add', [AdminBrandCategoryController::class, 'add'])->name('brand.category.add');

        Route::get('/brands/categories/edit/{brandId}', [AdminBrandCategoryController::class, 'edit'])->name('brand.category.edit');

        Route::post('/brands/categories/store', [AdminBrandCategoryController::class, 'store'])->name('brand.category.store');

        Route::post('/brands/categories/update', [AdminBrandCategoryController::class, 'update'])->name('brand.category.update');

        Route::get('/brands/categories/delete/{brandId}', [AdminBrandCategoryController::class, 'delete'])->name('brand.category.delete');

        Route::post('/brands/categories/delete', [AdminBrandCategoryController::class, 'deleteMultiple'])->name('brand.categories.delete');

        Route::get('/brands/products/list', [AdminBrandProductController::class, 'list'])->name('brand.product.list');

        Route::get('/brands/products', [AdminBrandProductController::class, 'getBrandProducts'])->name('get.brand.products');

        Route::get('/brands/products/add', [AdminBrandProductController::class, 'add'])->name('brand.product.add');

        Route::post('/brands/products/image/upload', [AdminBrandProductController::class, 'upload'])->name('brand.product.image.upload');

        Route::post('/brands/products/store', [AdminBrandProductController::class, 'store'])->name('brand.product.store');

        Route::post('/brands/products/delete', [AdminBrandProductController::class, 'deleteMultiple'])->name('brand.products.delete');

        Route::get('/brands/products/edit/{productId}', [AdminBrandProductController::class, 'edit'])->name('brand.product.edit');

        Route::post('/brands/products/update', [AdminBrandProductController::class, 'update'])->name('brand.product.update');

        Route::get('/brands/products/delete/{brandId}', [AdminBrandProductController::class, 'delete'])->name('brand.product.delete');

        Route::get('/catalogs/categories/list', [AdminCatalogCategoryController::class, 'list'])->name('catalog.category.list');

        Route::get('/catalogs/categories/add', [AdminCatalogCategoryController::class, 'add'])->name('catalog.category.add');

        Route::get('/catalogs/categories', [AdminCatalogCategoryController::class, 'getCatalogCategories'])->name('get.catalog.categories');

        Route::post('/catalogs/categories/delete', [AdminCatalogCategoryController::class, 'deleteMultiple'])->name('catalog.categories.delete');

        Route::get('/catalogs/categories/edit/{categoryId}', [AdminCatalogCategoryController::class, 'edit'])->name('catalog.category.edit');

        Route::post('/catalogs/categories/store', [AdminCatalogCategoryController::class, 'store'])->name('catalog.category.store');

        Route::post('/catalogs/categories/update', [AdminCatalogCategoryController::class, 'update'])->name('catalog.category.update');

        Route::get('/catalogs/categories/delete/{categoryId}', [AdminCatalogCategoryController::class, 'delete'])->name('catalog.category.delete');

        Route::get('/catalogs/products/list', [AdminCatalogProductController::class, 'list'])->name('catalog.product.list');

        Route::get('/catalogs/products', [AdminCatalogProductController::class, 'getCatalogProducts'])->name('get.catalog.products');

        Route::get('/catalogs/products/add', [AdminCatalogProductController::class, 'add'])->name('catalog.product.add');

        Route::post('/catalogs/products/delete', [AdminCatalogProductController::class, 'deleteMultiple'])->name('catalog.products.delete');

        Route::get('/catalogs/products/edit/{productId}', [AdminCatalogProductController::class, 'edit'])->name('catalog.product.edit');

        Route::post('/catalogs/products/store', [AdminCatalogProductController::class, 'store'])->name('catalog.product.store');

        Route::post('/catalogs/products/update', [AdminCatalogProductController::class, 'update'])->name('catalog.product.update');

        Route::get('/catalogs/products/delete/{brandId}', [AdminCatalogProductController::class, 'delete'])->name('catalog.product.delete');

        Route::get('/profile/get', [AdminController::class, 'getProfile'])->name(name: 'get.profile');

        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name(name: 'profile.update');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

    });

});
