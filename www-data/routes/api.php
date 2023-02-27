<?php

use App\Http\Controllers\Admin\Api\V1\AppController;
use App\Http\Controllers\Front\Api\V1\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//Route::post('auth/login', 'AuthController@login')->name('login');

Route::group(['namespace' => 'Admin\Api\V1', 'prefix' => 'v1', 'as' => 'api.', 'middleware' => 'api'], function () {
    Route::post('auth/login', 'AuthController@login')->name('auth.login');
});

//open api
Route::group(['namespace' => 'Front\Api\V1', 'prefix' => 'v1', 'as' => 'api.'], function () {
    Route::get('download/{id}', 'GeneralController@download')->name('download');
    Route::get('launch/{id}', [GeneralController::class, 'launch'])->name('launch')->middleware('token');
    Route::get('view/{id}', 'GeneralController@view')->name('view');
    Route::get('twa/{id}', 'GeneralController@twa')->name('twa');
    Route::any('event_hskwnk1/{id}/{eventName}', 'GeneralController@event')->name('event');
    Route::any('app_errors/{id}/', 'GeneralController@app_errors')->name('err');
    Route::any('usrtr', 'GeneralController@usrtr')->name('usrtr');
    Route::any('setstate', [AppController::class,'setstate'])->middleware('token');
});


//admin API
Route::group(['namespace' => 'Admin\Api\V1', 'prefix' => 'admin/v1', 'as' => 'api.', 'middleware' => 'auth:api'], function () {
    Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');
    Route::get('profile/me', 'AuthController@me')->name('profile.me');




    Route::get('users', 'UsersController@index')->name('users.list');
    Route::get('users/meta', 'UsersController@meta')->name('users.meta');
    Route::post('users/create', 'UsersController@create')->name('users.create');
    Route::get('users/read/{id}', 'UsersController@read')->name('users.read');
    Route::put('users/update/{id}', 'UsersController@update')->name('users.update');
    Route::delete('users/delete/{id}', 'UsersController@delete')->name('users.delete');

    Route::post('tools/upload', 'ToolsController@upload')->name('tools.upload');



    Route::get('app', 'AppController@index')->name('app.list');
    Route::get('app/meta', 'AppController@meta')->name('app.meta');
    Route::post('app/create', 'AppController@create')->name('app.create');
    Route::get('app/read/{id}', 'AppController@read')->name('app.read');
    Route::put('app/update/{id}', 'AppController@update')->name('app.update');
    Route::delete('app/delete/{id}', 'AppController@delete')->name('app.delete');

    Route::get('client', 'ClientController@index')->name('client.list');
    Route::get('client/meta', 'ClientController@meta')->name('client.meta');
    Route::post('client/create', 'ClientController@create')->name('client.create');
    Route::get('client/read/{id}', 'ClientController@read')->name('client.read');
    Route::put('client/update/{id}', 'ClientController@update')->name('client.update');
    Route::delete('client/delete/{id}', 'ClientController@delete')->name('client.delete');

    Route::get('settings', 'SettingController@index')->name('settings.list');
    Route::get('settings/meta', 'SettingController@meta')->name('settings.meta');
    Route::post('settings/create', 'SettingController@create')->name('settings.create');
    Route::get('settings/read/{id}', 'SettingController@read')->name('settings.read');
    Route::put('settings/update/{id}', 'SettingController@update')->name('settings.update');
    Route::delete('settings/delete/{id}', 'SettingController@delete')->name('settings.delete');

    Route::get('views', 'ViewController@index')->name('views.list');
    Route::get('views/meta', 'ViewController@meta')->name('views.meta');
    Route::post('views/create', 'ViewController@create')->name('views.create');
    Route::get('views/read/{id}', 'ViewController@read')->name('views.read');
    Route::put('views/update/{id}', 'ViewController@update')->name('views.update');
    Route::delete('views/delete/{id}', 'ViewController@delete')->name('views.delete');

//@newroutes
});
