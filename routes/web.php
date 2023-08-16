<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
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
    return view('welcome');
});

Route::prefix('dashboard')->group(function () {
    Route::get("", function () {
        return view("dashboard.index");
    });
    // Category
    Route::group(['prefix' => 'category', 'controller' => CategoryController::class], function() {
        Route::get('', 'index')->name('category.index');
        Route::post('store', 'store')->name('category.store');
        Route::post('data', 'getData')->name('category.data');
    });
    // Settings
    Route::group(['prefix' => 'settings', 'controller' => SettingController::class], function() {
        Route::get('home', 'home')->name('setting.home');
        Route::get('information', 'information')->name('setting.information');
        Route::post('data', 'data')->name('setting.home.data');
        Route::put('update/{prefix}', 'updateSetting')->name('setting.home.update');
    });
    // Teams
    Route::group(['prefix' => 'team', 'controller' => TeamController::class], function() {
        Route::get('', 'index')->name('team.index');
        Route::post('store', 'store')->name('team.store');
        Route::post('data', 'getData')->name('team.data');
    });
});
