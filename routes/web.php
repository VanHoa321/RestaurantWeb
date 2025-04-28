<?php

use App\Http\Controllers\backend\catalog\MenuController;
use App\Http\Controllers\backend\item\UnitController;
use App\Http\Controllers\backend\restaurant\BlogCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\CustomerAccountController;
use App\Http\Controllers\account\ForgotPasswordController;
use App\Http\Controllers\backend\booking\BookingHistoryController;
use App\Http\Controllers\Backend\booking\PresentController;
use App\Http\Controllers\Backend\booking\TableDiaController;
use App\Http\Controllers\backend\catalog\ComboController;
use App\Http\Controllers\backend\customer\CustomerController;
use App\Http\Controllers\backend\invoice\InvoiceController;
use App\Http\Controllers\backend\invoice\InvoiceHistoryController;
use App\Http\Controllers\backend\item\ItemCategoryController;
use App\Http\Controllers\backend\item\ItemController;
use App\Http\Controllers\backend\item\ItemPriceController;
use App\Http\Controllers\backend\restaurant\AreaController;
use App\Http\Controllers\backend\restaurant\BlogController;
use App\Http\Controllers\backend\restaurant\BranchController;
use App\Http\Controllers\backend\restaurant\ResInfoController;
use App\Http\Controllers\backend\restaurant\RestaurantController;
use App\Http\Controllers\backend\restaurant\TableController;
use App\Http\Controllers\backend\restaurant\TableTypeController;
use App\Http\Controllers\backend\statistical\DashboardController;
use App\Http\Controllers\Backend\System\AdminMenuController;
use App\Http\Controllers\Backend\User\GroupController;
use App\Http\Controllers\frontend\ContactController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\backend\system\SlideController;
use App\Http\Controllers\backend\user\UserController;
use App\Http\Controllers\frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\ItemController as FrontendItemController;
use App\Http\Controllers\frontend\OrderController;
use App\Http\Controllers\frontend\ResInfoController as FrontendResInfoController;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[HomeController::class, "index"]);

Route::group(['prefix' => 'files-manager'], function () {
    Lfm::routes();
});

//Web
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/info', [FrontendResInfoController::class, 'index'])->name('info.index');

//Frontend Item
Route::get('/item', [FrontendItemController::class, 'index'])->name('ft-item.index');
Route::get('/getData', [FrontendItemController::class, 'getData']);
Route::get('/getItem/{id}', [FrontendItemController::class, 'getItem']);
Route::get('/getCombo/{id}', [FrontendItemController::class, 'getCombo']);

//Frontend Blog
Route::get('/blog', [FrontendBlogController::class, 'index'])->name('ft-blog.index');
Route::get('/blog-getData', [FrontendBlogController::class, 'getData']);
Route::get('/blog/detail/{id}', [FrontendBlogController::class, 'detail'])->name('ft-blog.detail');

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/get', [CartController::class, 'getCart'])->name('cart.get');

//Order
Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/vnpay_payment', [OrderController::class, 'vnpay_payment'])->name('vnpay_payment');
Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/booking-success', [OrderController::class, 'bookingSuccess']);
//Account
Route::get('/login', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

//Customer Account
Route::get('/google/redirect', [CustomerAccountController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [CustomerAccountController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/cus-logout', [CustomerAccountController::class, 'logout'])->name('cus.logout');

//Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/new-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

//Profile
Route::get('/profile', [AccountController::class, 'profile']) ->name('profile');
Route::get('/edit-profile', [AccountController::class, 'editProfile']) ->name('edit-profile');
Route::post('/profile/update', [AccountController::class, 'updateProfile']) -> name('updateProfile');
Route::get('/change-password', [AccountController::class, 'editPassword']) ->name('editPassword');
Route::post('/update-password', [AccountController::class, 'updatePassword']) -> name('updatePassword');

//Home
Route::prefix('trang-chu')->group(function () {
    Route::get('/index', [AdminMenuController::class, 'index'])->name('menu.index')->middleware('role');
});

Route::prefix('statistical')->middleware('role')->group(function () {
    //Statistical
    Route::get('/index', [DashboardController::class, 'index'])->name('dashboard.index');
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

    //Slide
    Route::prefix('slide')->group(function () {
        Route::get('/index', [SlideController::class, 'index'])->name('slide.index');
        Route::get('/create', [SlideController::class, 'create'])->name('slide.create');
        Route::post('/store', [SlideController::class, 'store'])->name('slide.store');
        Route::get('/edit/{id}', [SlideController::class, 'edit'])->name('slide.edit');
        Route::post('/update/{id}', [SlideController::class, 'update'])->name('slide.update');
        Route::delete('/destroy/{id}', [SlideController::class, 'destroy']);
        Route::post('/change/{id}', [SlideController::class, 'changeActive']);
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

    //List User
    Route::prefix('list')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name("user.show");
        Route::post('/change/{id}', [UserController::class, 'changeActive']);
    });
});

Route::prefix('customer')->middleware('role')->group(function () {
    //customer
    Route::prefix('list')->group(function () {
        Route::get('/index', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::delete('/destroy/{id}', [CustomerController::class, 'destroy']);
        Route::post('/change/{id}', [CustomerController::class, 'changeActive']);
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

    //Unit
    Route::prefix('unit')->group(function () {
        Route::get('/index', [UnitController::class, 'index'])->name('unit.index');
        Route::get('/create', [UnitController::class, 'create'])->name('unit.create');
        Route::post('/store', [UnitController::class, 'store'])->name('unit.store');
        Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
        Route::post('/update/{id}', [UnitController::class, 'update'])->name('unit.update');
        Route::delete('/destroy/{id}', [UnitController::class, 'destroy']);
        Route::post('/change/{id}', [UnitController::class, 'changeActive']);
    });

    //Item
    Route::prefix('item')->group(function () {
        Route::get('/index', [ItemController::class, 'index'])->name('item.index');
        Route::get('/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/store', [ItemController::class, 'store'])->name('item.store');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('item.edit');
        Route::post('/update/{id}', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/destroy/{id}', [ItemController::class, 'destroy']);
        Route::post('/change/{id}', [ItemController::class, 'changeActive']);
    });

    //ItemPrice
    Route::prefix('item-price')->group(function () {
        Route::post('/store', [ItemPriceController::class, 'store']);
        Route::get('/edit/{id}', [ItemPriceController::class, 'edit']);
        Route::post('/update/{id}', [ItemPriceController::class, 'update']);
        Route::delete('/destroy/{id}', [ItemPriceController::class, 'destroy']);
        Route::post('/change/{id}', [ItemPriceController::class, 'changeActive']);
    });
});

Route::prefix('catalog')->middleware('role')->group(function () {
    //Menu
    Route::prefix('menu')->group(function () {
        Route::get('/index', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('/store', [MenuController::class, 'store']);;
        Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menu.edit');
        Route::post('/update/{id}', [MenuController::class, 'update']);
        Route::delete('/destroy/{id}', [MenuController::class, 'destroy']);
        Route::post('/change/{id}', [MenuController::class, 'changeActive']);
    });

    //Combo
    Route::prefix('combo')->group(function () {
        Route::get('/index', [ComboController::class, 'index'])->name('combo.index');
        Route::get('/create', [ComboController::class, 'create'])->name('combo.create');
        Route::post('/store', [ComboController::class, 'store']);;
        Route::get('/edit/{id}', [ComboController::class, 'edit'])->name('combo.edit');
        Route::post('/update/{id}', [ComboController::class, 'update']);
        Route::delete('/destroy/{id}', [ComboController::class, 'destroy']);
        Route::post('/change/{id}', [ComboController::class, 'changeActive']);
    });
});

Route::prefix('restaurant')->middleware('role')->group(function () {
    Route::get('/index', [RestaurantController::class, 'index'])->name('restaurant.index');

    //Info
    Route::prefix('info')->group(function () {
        Route::get('/edit/{id}', [ResInfoController::class, 'edit'])->name('info.edit');
        Route::post('/update/{id}', [ResInfoController::class, 'update'])->name('info.update');
    });

    //Branch
    Route::prefix('branch')->group(function () {
        Route::get('/index', [BranchController::class, 'index'])->name('branch.index');
        Route::get('/create', [BranchController::class, 'create'])->name('branch.create');
        Route::post('/store', [BranchController::class, 'store'])->name('branch.store');
        Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('branch.edit');
        Route::post('/update/{id}', [BranchController::class, 'update'])->name('branch.update');
        Route::delete('/destroy/{id}', [BranchController::class, 'destroy']);
        Route::post('/change/{id}', [BranchController::class, 'changeActive']);
    });

    //Table Type
    Route::prefix('table-type')->group(function () {
        Route::get('/index', [TableTypeController::class, 'index'])->name('table-type.index');
        Route::get('/create', [TableTypeController::class, 'create'])->name('table-type.create');
        Route::post('/store', [TableTypeController::class, 'store'])->name('table-type.store');
        Route::get('/edit/{id}', [TableTypeController::class, 'edit'])->name('table-type.edit');
        Route::post('/update/{id}', [TableTypeController::class, 'update'])->name('table-type.update');
        Route::delete('/destroy/{id}', [TableTypeController::class, 'destroy']);
        Route::post('/change/{id}', [TableTypeController::class, 'changeActive']);
    });

    //Area
    Route::prefix('area')->group(function () {
        Route::get('/index', [AreaController::class, 'index'])->name('area.index');
        Route::get('/create', [AreaController::class, 'create'])->name('area.create');
        Route::post('/store', [AreaController::class, 'store'])->name('area.store');
        Route::get('/edit/{id}', [AreaController::class, 'edit'])->name('area.edit');
        Route::post('/update/{id}', [AreaController::class, 'update'])->name('area.update');
        Route::delete('/destroy/{id}', [AreaController::class, 'destroy']);
        Route::post('/change/{id}', [AreaController::class, 'changeActive']);
    });

    //Table
    Route::prefix('table')->group(function () {
        Route::get('/index', [TableController::class, 'index'])->name('table.index');
        Route::get('/create', [TableController::class, 'create'])->name('table.create');
        Route::post('/store', [TableController::class, 'store'])->name('table.store');
        Route::get('/edit/{id}', [TableController::class, 'edit'])->name('table.edit');
        Route::post('/update/{id}', [TableController::class, 'update'])->name('table.update');
        Route::delete('/destroy/{id}', [TableController::class, 'destroy']);
        Route::post('/change/{id}', [TableController::class, 'changeActive']);
    });

    //Blog-Category
    Route::prefix('blog-category')->group(function () {
        Route::get('/index', [BlogCategoryController::class, 'index'])->name('blog-category.index');
        Route::get('/create', [BlogCategoryController::class, 'create'])->name('blog-category.create');
        Route::post('/store', [BlogCategoryController::class, 'store'])->name('blog-category.store');
        Route::get('/edit/{id}', [BlogCategoryController::class, 'edit'])->name('blog-category.edit');
        Route::post('/update/{id}', [BlogCategoryController::class, 'update'])->name('blog-category.update');
        Route::delete('/destroy/{id}', [BlogCategoryController::class, 'destroy']);
        Route::post('/change/{id}', [BlogCategoryController::class, 'changeActive']);
    });

    //Blog
    Route::prefix('blog')->group(function () {
        Route::get('/index', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
        Route::post('/update/{id}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('/destroy/{id}', [BlogController::class, 'destroy']);
        Route::post('/change/{id}', [BlogController::class, 'changeActive']);
    });
});

Route::prefix('booking')->middleware('role')->group(function () {

    Route::prefix('present')->group(function () {
        Route::get('/index', [PresentController::class, 'index'])->name('present.index');
        Route::get('/table-arrangement/{id}', [PresentController::class, 'tableArrangement'])->name('present.table');
        Route::get('/post-arrangement/{id}/{table_id}', [PresentController::class, 'postArrangement'])->name('post.table');
        Route::post('/cancel/{id}', [PresentController::class, 'cancelBooking'])->name("present.cancelBooking");
        Route::get('/select-item/{id}', [PresentController::class, 'selectItem'])->name('present.select-item');
        Route::post('/update-item', [PresentController::class, 'updateItem']);
        Route::get('/customer-table/{id}', [PresentController::class, 'customerTable'])->name("present.customerTable");
    });

    Route::prefix('table-dia')->group(function () {
        Route::get('/index', [TableDiaController::class, 'index'])->name('table-dia.index');
        Route::get('/getBooking/{id}', [TableDiaController::class, 'getBookingByTableId']);
        Route::get('/getInvoice/{id}', [TableDiaController::class, 'getInvoiceByTableId']);
    });

    Route::prefix('history')->group(function () {
        Route::get('/index', [BookingHistoryController::class, 'index'])->name('booking-history.index');
        Route::get('/detail/{id}', [BookingHistoryController::class, 'detail'])->name('booking-history.detail');
    });
});

Route::prefix('invoice')->middleware('role')->group(function () {

    Route::prefix('present')->group(function () {
        Route::get('/index', [InvoiceController::class, 'index'])->name('inv-present.index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('inv-present.create');
        Route::post('/store', [InvoiceController::class, 'store'])->name('inv-present.store');
        Route::get('/table-arrangement/{id}', [InvoiceController::class, 'tableArrangement'])->name('inv-present.table');
        Route::get('/post-arrangement/{id}/{table_id}', [InvoiceController::class, 'postArrangement'])->name('inv-post.table');
        Route::post('/cancel/{id}', [InvoiceController::class, 'cancelInvoice'])->name("inv-present.cancelBooking");
        Route::get('/select-item/{id}', [InvoiceController::class, 'selectItem'])->name('inv-present.select-item');
        Route::post('/update-item', [InvoiceController::class, 'updateItem']);
        Route::get('/get-invoice/{id}', [InvoiceController::class, 'getInvoice']);
        Route::post('/pay-invoice/{id}', [InvoiceController::class, 'paymentInvoice']);
        Route::get('/print/{id}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');
    });

    Route::prefix('history')->group(function () {
        Route::get('/index', [InvoiceHistoryController::class, 'index'])->name('invoice-history.index');
        Route::get('/detail/{id}', [InvoiceHistoryController::class, 'detail'])->name('invoice-history.detail');
    });
});