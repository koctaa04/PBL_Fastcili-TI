<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\MabacController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\VerifikasiLaporanController;
use App\Http\Controllers\FasilitasController;

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

Route::get('/mabac', [MabacController::class, 'index']);

Route::get('/verifikasi', [VerifikasiLaporanController::class, 'index'])->name('prioritas.index');
Route::get('/verifikasi/true/{id}', [VerifikasiLaporanController::class, 'verif']);
Route::post('/verifikasi/konfirm/{id}', [VerifikasiLaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
Route::get('/verifikasi/false/{id}', [VerifikasiLaporanController::class, 'tolakForm']);
Route::put('/verifikasi/tolak/{id}', [VerifikasiLaporanController::class, 'tolak'])->name('laporan.tolak');

Route::get('/level', [LevelController::class, 'index'])->name('level.index');
Route::get('/level/create', [LevelController::class, 'create']);
Route::post('/level', [LevelController::class, 'store'])->name('level.store');
Route::get('/level/edit/{id}', [LevelController::class, 'edit']);
Route::put('/level/update/{id}', [LevelController::class, 'update']);
Route::delete('/level/delete/{id}', [LevelController::class, 'destroy']);

Route::get('/lapor_kerusakan', [LaporanKerusakanController::class, 'index'])->name('perbaikan.index');
Route::get('/lapor_kerusakan/create', [LaporanKerusakanController::class, 'create']);
Route::post('/lapor_kerusakan', [LaporanKerusakanController::class, 'store'])->name('laporan.store');
Route::get('/lapor_kerusakan/edit/{id}', [LaporanKerusakanController::class, 'edit']);
Route::put('/lapor_kerusakan/update/{id}', [LaporanKerusakanController::class, 'update']);
Route::delete('/lapor_kerusakan/delete/{id}', [LaporanKerusakanController::class, 'destroy']);

// Route::get('/get-ruangan/{id_gedung}', [LaporanKerusakanController::class, 'getRuangan']);
// Route::get('/get-fasilitas/{id_ruangan}', [LaporanKerusakanController::class, 'getFasilitas']);
Route::get('/get-ruangan/{id}', [LaporanKerusakanController::class, 'getRuangan']);
Route::get('/get-fasilitas/{id}', [LaporanKerusakanController::class, 'getFasilitas']);

Route::get('/gedung1', [GedungController::class, 'index']);

Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
Route::get('/ruangan/create', [RuanganController::class, 'create']);
Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
Route::get('/ruangan/edit/{id}', [RuanganController::class, 'edit']);
Route::put('/ruangan/update/{id}', [RuanganController::class, 'update']);
Route::delete('/ruangan/delete/{id}', [RuanganController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [UserController::class, 'edit']);
Route::put('/users/update/{id}', [UserController::class, 'update']);
Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
Route::get('/fasilitas/create', [FasilitasController::class, 'create']);
Route::post('/fasilitas', [FasilitasController::class, 'store'])->name('fasilitas.store');
Route::get('/fasilitas/edit/{id}', [FasilitasController::class, 'edit']);
Route::put('/fasilitas/update/{id}', [FasilitasController::class, 'update']);
Route::delete('/fasilitas/delete/{id}', [FasilitasController::class, 'destroy']);

Route::get('/', function () {
	return view('welcome');
});

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

// Password Reset Routes...
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@updatepassword']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
