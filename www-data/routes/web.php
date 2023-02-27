<?php

use App\Http\Controllers\Admin\AdminNotificstionsController;
use App\Http\Controllers\Admin\AppsController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\UserRoleAdminController;
use App\Http\Controllers\Admin\UserRolePermissionAdminController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\UsersLogsController;
use App\Http\Middleware\IfAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(auth()->check()){
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/toggleDark', function () {
    if(session()->has('dark')){
        session()->forget('dark');
    }
    else {
        session()->put('dark', true);
    }
    return redirect()->back();
})
->name('toggleDark');

Auth::routes();


Route::prefix('admin')
    ->name('admin.')
    ->middleware(IfAuth::class)
    ->group(function (){
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/backups', [App\Http\Controllers\Admin\BackupsController::class, 'index'])->name('backups');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'view'])->name('dashboard');
        Route::get('/dashboard/analytics', [App\Http\Controllers\Admin\DashboardController::class, 'analytics'])->name('dashboard.analytics');
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'view'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('settings.store');

        Route::prefix('apps')
            ->name('apps.')
            ->group(function (){
                Route::get('list', [AppsController::class, 'list'])->name('list');
                Route::any('listData', [AppsController::class, 'data'])->name('listData');
                Route::get('listTrashed', [AppsController::class, 'listTrashed'])->name('listTrashed');
                Route::any('listTrashedData', [AppsController::class, 'listTrashedData'])->name('listTrashedData');
                Route::get('create', [AppsController::class, 'add'])->name('create');
                Route::get('edit/{app}', [AppsController::class, 'view'])->name('view');
                Route::get('delete/{app}', [AppsController::class, 'delete'])->name('delete');
                Route::get('restore/{app}', [AppsController::class, 'restore'])->name('restore');
                Route::post('store', [AppsController::class, 'store'])->name('store');
            });

        Route::prefix('users')
            ->name('users.')
            ->group(function (){
                Route::get('list', [UsersController::class, 'list'])->name('list');
                Route::any('listData', [UsersController::class, 'listData'])->name('listData');
                Route::get('create', [UsersController::class, 'add'])->name('create');
                Route::get('edit/{user}', [UsersController::class, 'view'])->name('view');
                Route::get('delete/{user}', [UsersController::class, 'delete'])->name('delete');
                Route::post('store', [UsersController::class, 'store'])->name('store');
            });

        //rolePermissions

        Route::prefix('role-permissions')->group(function () {
            Route::get('/', [UserRolePermissionAdminController::class, 'listView'])->name('role_permissions_list_view');
            Route::any('/data', [UserRolePermissionAdminController::class, 'viewData'])->name('role_permissions_list_view_data');

            Route::get('/add', [UserRolePermissionAdminController::class, 'addView'])->name('add_view_role_permissions');
            Route::post('/store/{key}', [UserRolePermissionAdminController::class, 'store'])->name('store_role_permissions');
            Route::get('/view/{id}', [UserRolePermissionAdminController::class, 'editView'])->name('view_role_permissions');
            Route::get('/delete/{id}', [UserRolePermissionAdminController::class, 'delete'])->name('delete_role_permissions');
        });



        //roles

        Route::prefix('user-roles')->group(function () {
            Route::get('/', [UserRoleAdminController::class, 'listView'])->name('role_list_view');
            Route::any('/data', [UserRoleAdminController::class, 'viewData'])->name('role_list_view_data');

            Route::get('/add', [UserRoleAdminController::class, 'addView'])->name('add_view_role');
            Route::post('/store', [UserRoleAdminController::class, 'store'])->name('store_role');
            Route::get('/view/{id}', [UserRoleAdminController::class, 'editView'])->name('view_role');
            Route::get('/delete/{id}', [UserRoleAdminController::class, 'delete'])->name('delete_role');
        });


        Route::prefix('clients')
            ->name('clients.')
            ->group(function (){
                Route::get('list', [ClientsController::class, 'list'])->name('list');
                Route::get('logs/{bid}', [ClientsController::class, 'logs'])->name('logs');
                Route::get('deleteFromBid/{bid}', [ClientsController::class, 'deleteFromBid'])->name('deleteFromBid');
                Route::any('listData', [ClientsController::class, 'listData'])->name('listData');
//                Route::get('create', [AppsController::class, 'create'])->name('create');
//                Route::get('edit/{app}', [AppsController::class, 'view'])->name('view');
//                Route::get('delete/{app}', [AppsController::class, 'delete'])->name('delete');
//                Route::post('store', [AppsController::class, 'store'])->name('store');
            });

        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function (){
                Route::get('list', [AdminNotificstionsController::class, 'list'])->name('list');
                Route::any('listData', [AdminNotificstionsController::class, 'listData'])->name('listData');
//                Route::get('create', [AppsController::class, 'create'])->name('create');
//                Route::get('edit/{app}', [AppsController::class, 'view'])->name('view');
//                Route::get('delete/{app}', [AppsController::class, 'delete'])->name('delete');
//                Route::post('store', [AppsController::class, 'store'])->name('store');
            });

        Route::prefix('logs')
            ->name('logs.')
            ->group(function (){
                Route::get('list', [LogsController::class, 'list'])->name('list');
                Route::any('listData', [LogsController::class, 'listData'])->name('listData');
//                Route::get('create', [AppsController::class, 'create'])->name('create');
//                Route::get('edit/{app}', [AppsController::class, 'view'])->name('view');
//                Route::get('delete/{app}', [AppsController::class, 'delete'])->name('delete');
//                Route::post('store', [AppsController::class, 'store'])->name('store');
            });
        Route::prefix('users-logs')
            ->name('users-logs.')
            ->group(function (){
                Route::get('list', [UsersLogsController::class, 'list'])->name('list');
                Route::any('listData', [UsersLogsController::class, 'data'])->name('listData');
//                Route::get('create', [AppsController::class, 'create'])->name('create');
//                Route::get('edit/{app}', [AppsController::class, 'view'])->name('view');
//                Route::get('delete/{app}', [AppsController::class, 'delete'])->name('delete');
//                Route::post('store', [AppsController::class, 'store'])->name('store');
            });

        // tokens
        Route::prefix('tokens')
            ->name('tokens.')
            ->group(function (){
                Route::get('/', [TokenController::class, 'index'])->name('list');
                Route::any('listData', [TokenController::class, 'data'])->name('listData');
                Route::get('create', [TokenController::class, 'create'])->name('create');
                Route::post('store', [TokenController::class, 'store'])->name('store');
                Route::get('edit/{token}', [TokenController::class, 'edit'])->name('edit');
                Route::get('destroy/{token}', [TokenController::class, 'destroy'])->name('destroy');
            });

        /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

        /**
         * FRONT
         */
//        Route::group(['namespace' => 'Front'], function(){
//            Route::get('/admin', 'AdminController@index')->name('front.admin');
//    //    Route::get('/', 'FrontController@index')->name('front.home');
//            Route::get('/show_errors', 'FrontController@show_errors')->name('show.errors');
//            Route::post('/show_errors', 'FrontController@show_errors')->name('show.errorsp');
//            Route::any('/show_usrtr', 'FrontController@show_usrtr')->name('show.usrtr');
//
//        });

});



