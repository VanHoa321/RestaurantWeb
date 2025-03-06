<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\backend\item\ItemCategoryController;
use App\Http\Controllers\Backend\System\AdminMenuController;
use App\Http\Controllers\Backend\User\GroupController;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[AccountController::class, "login"])->name("index");

Route::group(['prefix' => 'files-manager'], function () {
    Lfm::routes();
});

//Account
Route::get('/dang-nhap', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');
Route::get('/dang-xuat', [AccountController::class, 'logout'])->name('logout');

//Home
Route::prefix('trang-chu')->group(function () {
    Route::get('/index', [AdminMenuController::class, 'index'])->name('menu.index')->middleware('role');
});

Route::prefix('system')->middleware('role')->group(function () {
    //Admin Menu
    Route::prefix('admin-menu')->group(function () {
        Route::get('/index', [AdminMenuController::class, 'index'])->name('admin-menu.index');
        Route::get('/create', [AdminMenuController::class, 'create'])->name('admin-menu.create');
        Route::post('/store', [AdminMenuController::class, 'store'])->name('admin-menu.store');
        Route::get('/edit/{id}', [AdminMenuController::class, 'edit'])->name('admin-menu.edit');
        Route::post('/update/{id}', [AdminMenuController::class, 'update'])->name('admin-menu.update');
        Route::delete('/destroy/{id}', [AdminMenuController::class, 'destroy']);
        Route::post('/change/{id}', [AdminMenuController::class, 'changeActive']);
    });
});

Route::prefix('user')->middleware('role')->group(function () {
    //Group
    Route::prefix('group')->group(function () {
        Route::get('/index', [GroupController::class, 'index'])->name('group.index');
        Route::get('/edit/{id}', [GroupController::class, 'edit'])->name('group.edit');
        Route::post('/update/{id}', [GroupController::class, 'update'])->name('group.update');
        Route::get('/role/{id}', [GroupController::class, 'role'])->name('group.role');
        Route::post('/update-roles/{id}', [GroupController::class, 'updateRoles'])->name('group.updateRoles');
    });
});

Route::prefix('item')->middleware('role')->group(function () {
    //ItemCategory
    Route::prefix('item-category')->group(function () {
        Route::get('/index', [ItemCategoryController::class, 'index'])->name('item-category.index');
        Route::get('/create', [ItemCategoryController::class, 'create'])->name('item-category.create');
        Route::post('/store', [ItemCategoryController::class, 'store'])->name('item-category.store');
        Route::get('/edit/{id}', [ItemCategoryController::class, 'edit'])->name('item-category.edit');
        Route::post('/update/{id}', [ItemCategoryController::class, 'update'])->name('item-category.update');
        Route::delete('/destroy/{id}', [ItemCategoryController::class, 'destroy']);
        Route::post('/change/{id}', [ItemCategoryController::class, 'changeActive']);
    });

    //Item
});

