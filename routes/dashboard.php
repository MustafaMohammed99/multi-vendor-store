<?php

use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ImportProductsController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\StoresController;
use App\Http\Controllers\Dashboard\UsersController;
use Illuminate\Support\Facades\Route;

/*
compposer update
Class Hypweb\Flysystem\Cached\Extra\disableEnsureParentDirectories located in ./vendor/nao-pon/flysystem-cached-extra/src/DisableEnsureParentDirectories.php does not comply with psr-4 autoloading standard. Skipping.
*/

Route::group([
    'middleware' => 'auth:admin,web',
    'as' => 'dashboard.',
    'prefix' => 'admin/dashboard',
], function () {

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');


    Route::get('/categories/trash', [CategoriesController::class, 'trash'])
        ->name('categories.trash');
    Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])
        ->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])
        ->name('categories.force-delete');



    Route::get('/stores/trash', [StoresController::class, 'trash'])
        ->name('stores.trash');
    Route::put('stores/{store}/restore', [StoresController::class, 'restore'])
        ->name('stores.restore');
    Route::delete('stores/{store}/force-delete', [StoresController::class, 'forceDelete'])
        ->name('stores.force-delete');


    Route::get('/products/trash', [ProductsController::class, 'trash'])
        ->name('products.trash');
    Route::put('products/{product}/restore', [ProductsController::class, 'restore'])
        ->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductsController::class, 'forceDelete'])
        ->name('products.force-delete');


    // Route::get('products/import', [ImportProductsController::class, 'create'])
    //     ->name('products.import');
    // Route::post('products/import', [ImportProductsController::class, 'store']);

    Route::resources([
        'products' => ProductsController::class,
        'categories' => CategoriesController::class,
        'roles' => RolesController::class,
        'users' => UsersController::class,
        'admins' => AdminsController::class,
        'stores' => StoresController::class,
    ]);
});
