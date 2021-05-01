<?php

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

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['role:Super Admin']], function () {
	Route::resource('users','UserController');
	Route::resource('roles','RoleController');
});

Route::group(['middleware' => ['auth']], function () {

  Route::post("settings/resetpassword", 'SettingController@resetPassword')->name("settings.resetpassword");
	Route::resource('/settings', 'SettingController');

  Route::any('master/{action?}', function ($action = 'index'){
      $controller = app()->make('App\Http\Controllers\MasterController');
      return $controller->callAction($action,[request()]);
  });
  Route::any('transaksi/{action?}/{id?}', function ($action, $id = ""){
      $controller = app()->make('App\Http\Controllers\TransaksiController');
      if (ctype_digit($action) || trim($action == "")){
          $id = $action;
          $action = "schedule";
      }
      return $controller->callAction($action,[request(), $id]);
  });
	Route::any('gudang/{action?}/{id?}', function ($action, $id = ""){
			$controller = app()->make('App\Http\Controllers\GudangController');
			if (ctype_digit($action) || trim($action == "")){
					$id = $action;
					$action = "rekamData";
			}
			return $controller->callAction($action,[request(), $id]);
	});
});
