<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('whatsapp-messages', ['as' => 'pages.whatsapp.messages', 'uses' => 'App\Http\Controllers\WhatsAppController@reportes']);
	Route::get('whatsapp-estados', ['as' => 'pages.whatsapp.estados', 'uses' => 'App\Http\Controllers\WhatsAppController@WhatsappEstados']);
	Route::get('mensajesrespondidos', ['as' => 'pages.mensajesrespondidos', 'uses' => 'App\Http\Controllers\WhatsAppController@mensajesrespondidos']);
	Route::get('reporte-whatsapp-estados', ['as' => 'pages.estadoswhatsappreporte', 'uses' => 'App\Http\Controllers\WhatsAppController@estadoswhatsappreporte']);
	Route::get('reporte-whatsapp-mensajes-enviados',['as'=>'pages.mensajesenviadosdashboards','uses'=>'App\Http\Controllers\WhatsAppController@dashboardWhatsappmensajesenviados']);

	Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
	Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

