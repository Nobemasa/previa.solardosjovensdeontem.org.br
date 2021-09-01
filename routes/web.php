<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
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


Auth::routes([
    'register' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function() {
    Route::get('/fotos', 'FotoController@index')->name('fotos');
    Route::post('/fotos/add', 'FotoController@store')->name('add_fotos');
    Route::delete('/fotos/del', 'FotoController@destroy')->name('del_fotos');

    Route::get('/videos', 'VideoController@index')->name('videos');
    Route::post('/videos/add', 'VideoController@store')->name('add_videos');
    Route::delete('/videos/del', 'VideoController@destroy')->name('del_videos');

    Route::get('/noticias', 'NoticiaController@index')->name('noticias');
    Route::post('/noticias/add', 'NoticiaController@store')->name('add_noticias');
    Route::delete('/noticias/del', 'NoticiaController@destroy')->name('del_noticias');

    Route::get('/parceiros', 'ParceiroController@index')->name('parceiros');
    Route::post('/parceiros/add', 'ParceiroController@store')->name('add_parceiros');
    Route::delete('/parceiros/del', 'ParceiroController@destroy')->name('del_parceiros');
});