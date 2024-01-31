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

Route::get('', function (){
    return redirect()->route('login');
});

Route::prefix('login')->name('login')->group(function (){
    Route::get('', 'LoginController@index');
    Route::post('/process', 'LoginController@login')->name('.process');
});

Route::post('logout', 'LoginController@logout')->name('logout');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::prefix('user')->name('user')->group(function () {
    Route::get('', 'UserController@index');
    Route::get('/datatable', 'UserController@datatable')->name('.datatable');
    Route::post('/store', 'UserController@store')->name('.store');
    Route::get('/get', 'UserController@get')->name('.get');
    Route::put('/update', 'UserController@update')->name('.update');
    Route::delete('/delete', 'UserController@delete')->name('.delete');
});

Route::prefix('realisasi')->name('realisasi')->group(function () {
    Route::get('', 'RealisasiController@submission');
    Route::get('/datatable', 'RealisasiController@datatableSubmission')->name('.datatable');

    Route::prefix('/{id}')->name('.detail')->group(function () {
        Route::get('', 'RealisasiController@index');
        Route::get('/export', 'RealisasiController@export')->name('.export');
        Route::get('/exportexcel', 'RealisasiController@exportexcel')->name('.exportexcel');
        Route::get('/exportpdf', 'RealisasiController@exportpdf')->name('.exportpdf');
        Route::post('/import', 'RealisasiController@import')->name('.import');
        Route::get('/datatable', 'RealisasiController@datatable')->name('.datatable');
        Route::get('/get-barang', 'RealisasiController@getBarang')->name('.get_barang');
        Route::get('/get-item', 'RealisasiController@getItem')->name('.get_item');
        Route::post('/store', 'RealisasiController@store')->name('.store');
        Route::get('/get', 'RealisasiController@get')->name('.get');
        Route::post('/update', 'RealisasiController@update')->name('.update');
        Route::delete('/delete', 'RealisasiController@delete')->name('.delete');
    });
});

Route::prefix('pengajuan')->name('pengajuan')->group(function () {
    Route::get('', 'PengajuanController@index');
    Route::get('/datatable', 'PengajuanController@datatable')->name('.datatable');
    Route::post('/store', 'PengajuanController@store')->name('.store');
    Route::get('/get', 'PengajuanController@get')->name('.get');
    Route::get('/get-prodi', 'PengajuanController@getProdi')->name('.get_prodi');
    Route::put('/update', 'PengajuanController@update')->name('.update');
    Route::delete('/delete', 'PengajuanController@delete')->name('.delete');

    Route::prefix('/{id}')->name('.detail')->group(function () {
        Route::get('', 'PengajuanDetailController@index');
        Route::get('/pengajuan', 'PengajuanDetailController@pengajuan')->name('.pengajuan');
        Route::get('/negosiasi', 'PengajuanDetailController@negosiasi')->name('.negosiasi');
        Route::get('/realisasi', 'PengajuanDetailController@realisasi')->name('.realisasi');
        Route::get('/datatable', 'PengajuanDetailController@datatable')->name('.datatable');
        Route::post('/store', 'PengajuanDetailController@store')->name('.store');
        Route::get('/get', 'PengajuanDetailController@get')->name('.get');
        Route::post('/update', 'PengajuanDetailController@update')->name('.update');
        Route::post('/update-negosiasi', 'PengajuanDetailController@updateNegotiation')->name('.update_negotiation');
        Route::delete('/delete', 'PengajuanDetailController@delete')->name('.delete');
        Route::get('/export', 'PengajuanDetailController@export')->name('.export');
        Route::get('/exportexcel', 'PengajuanDetailController@exportexcel')->name('.exportexcel');
        Route::get('/exportpdf', 'PengajuanDetailController@exportpdf')->name('.exportpdf');
        Route::post('/import', 'PengajuanDetailController@import')->name('.import');
    });
});

Route::prefix('prodi')->name('prodi')->group(function () {
    Route::get('', 'ProdiController@index');
    Route::get('/datatable', 'ProdiController@datatable')->name('.datatable');
    Route::post('/store', 'ProdiController@store')->name('.store');
    Route::get('/get', 'ProdiController@get')->name('.get');
    Route::put('/update', 'ProdiController@update')->name('.update');
    Route::put('/update-kaprodi', 'ProdiController@updateKaprodi')->name('.update_kaprodi');
    Route::delete('/delete', 'ProdiController@delete')->name('.delete');
});

Route::prefix('option')->name('option')->group(function () {
    Route::get('', 'OptionController@index');
    Route::post('/update-password', 'OptionController@updatePassword')->name('.updatePassword');
});
