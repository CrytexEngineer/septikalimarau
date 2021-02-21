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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();


Route::get('/', 'App\Http\Controllers\ReportController@index')->name('home')->middleware('auth');


Route::middleware('can:management')->group(function () {


    Route::get('/unit/json', '\App\Http\Controllers\UnitController@json');
    Route::resource('/unit', 'App\Http\Controllers\UnitController');


    Route::get('/task/json', '\App\Http\Controllers\TaskController@json');
    Route::resource('/task', 'App\Http\Controllers\TaskController');

    Route::get('/item/json', '\App\Http\Controllers\ItemsController@json');
    Route::resource('/item', 'App\Http\Controllers\ItemsController');
});

Route::middleware('can:admin')->group(function () {
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

Route::middleware('can:user')->group(function () {

    Route::get('/user/json', '\App\Http\Controllers\UserController@json');
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);


    Route::get('/report/export/{report_id}', '\App\Http\Controllers\ReportController@saveExport')->name("report.export");
    Route::get('/report/archive', '\App\Http\Controllers\ReportController@archive')->name("report.archive");
    Route::get('/report/review', '\App\Http\Controllers\ReportController@review')->name("report.review");
    Route::get('/report/json', '\App\Http\Controllers\ReportController@json');
    Route::patch('/report/mass_update','\App\Http\Controllers\ReportController@mass_update')->name("report.mass_update");
    Route::delete('/report/mass_delete','\App\Http\Controllers\ReportController@mass_delete')->name("report.mass_delete");
    Route::resource('/report', 'App\Http\Controllers\ReportController');

    Route::get('/record/json', '\App\Http\Controllers\RecordController@json');
    Route::resource('/record', 'App\Http\Controllers\RecordController');


    Route::post('/lampiran/upload', 'App\Http\Controllers\RecordController@upload_lampiran');
    Route::delete('/lampiran/hapus/{id}', 'App\Http\Controllers\RecordController@hapus');
    Route::patch('/lampiran/update', 'App\Http\Controllers\RecordController@update_lampiran');

    Route::get('/taskquery', 'App\Http\Controllers\FilterHelperController@taskQuery')->name("filter.taskQuery");
});
