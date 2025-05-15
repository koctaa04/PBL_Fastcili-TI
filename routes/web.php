<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MabacController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\VerifikasiLaporanController;
use App\Http\Controllers\HomeController;

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


/** -----------------------------
 *  Welcome Page
 *  ---------------------------- */
Route::get('/', function () {
	if (Auth::check()) {
		return redirect()->route('home');
	}
	return view('welcome');
});

Auth::routes();


// Password Reset Routes...
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', [HomeController::class, 'index'])->name('home');

	/** -----------------------------
	 *  Profile & User Management
	 *  ---------------------------- */
	Route::resource('user', UserController::class)->except(['show']);

	Route::prefix('profile')->group(function () {
		Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
		Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
		Route::put('/password', [ProfileController::class, 'updatepassword'])->name('profile.password');
	});

	/** -----------------------------
	 *  Kelola Level
	 *  ---------------------------- */
	Route::prefix('level')->group(function () {
		Route::get('/', [LevelController::class, 'index'])->name('level.index');
		Route::get('/create', [LevelController::class, 'create']);
		Route::post('/', [LevelController::class, 'store'])->name('level.store');
		Route::get('/edit/{id}', [LevelController::class, 'edit']);
		Route::put('/update/{id}', [LevelController::class, 'update']);
		Route::delete('/delete/{id}', [LevelController::class, 'destroy']);
	});

	/** -----------------------------
	 *  Kelola Pengguna
	 *  ---------------------------- */
	Route::prefix('users')->group(function () {
		Route::get('/', [UserController::class, 'index'])->name('users.index');
		Route::get('/create', [UserController::class, 'create']);
		Route::post('/', [UserController::class, 'store'])->name('users.store');
		Route::get('/edit/{id}', [UserController::class, 'edit']);
		Route::put('/update/{id}', [UserController::class, 'update']);
		Route::delete('/delete/{id}', [UserController::class, 'destroy']);
		Route::get('/import', [UserController::class, 'import'])->name('users.import');
		Route::post('/import_ajax', [UserController::class, 'import_ajax'])->name('users.import.ajax');
		Route::post('/toggle-access/{id}', [UserController::class, 'toggleAccess']);
	});

	/** -----------------------------
	 *  Gedung
	 *  ---------------------------- */
	Route::prefix('gedung')->group(function () {
		Route::get('/', [GedungController::class, 'index'])->name('gedung.index');
		Route::get('/create', [GedungController::class, 'create']);
		Route::post('/', [GedungController::class, 'store'])->name('gedung.store');
		Route::get('/edit/{id}', [GedungController::class, 'edit']);
		Route::put('/update/{id}', [GedungController::class, 'update'])->name('gedung.update');
		Route::delete('/delete/{id}', [GedungController::class, 'destroy']);
	});

	/** -----------------------------
	 *  Ruangan
	 *  ---------------------------- */
	Route::prefix('ruangan')->group(function () {
		Route::get('/', [RuanganController::class, 'index'])->name('ruangan.index');
		Route::get('/create', [RuanganController::class, 'create']);
		Route::post('/', [RuanganController::class, 'store'])->name('ruangan.store');
		Route::get('/edit/{id}', [RuanganController::class, 'edit']);
		Route::put('/update/{id}', [RuanganController::class, 'update']);
		Route::delete('/delete/{id}', [RuanganController::class, 'destroy']);
		Route::get('/ruangan/data', [RuanganController::class, 'getData'])->name('ruangan.data');
	});

	/** -----------------------------
	 *  Fasilitas
	 *  ---------------------------- */
	Route::prefix('fasilitas')->group(function () {
		Route::get('/', [FasilitasController::class, 'index'])->name('fasilitas.index');
		Route::get('/create', [FasilitasController::class, 'create']);
		Route::post('/', [FasilitasController::class, 'store'])->name('fasilitas.store');
		Route::get('/edit/{id}', [FasilitasController::class, 'edit']);
		Route::put('/update/{id}', [FasilitasController::class, 'update']);
		Route::delete('/delete/{id}', [FasilitasController::class, 'destroy']);
		Route::get('/get-ruangan/{id}', [FasilitasController::class, 'getRuangan']);
	});


	/** -----------------------------
	 *  Laporan Kerusakan
	 *  ---------------------------- */
	Route::prefix('lapor_kerusakan')->group(function () {
		Route::get('/', [LaporanKerusakanController::class, 'index'])->name('perbaikan.index');
		Route::get('/create', [LaporanKerusakanController::class, 'create']);
		Route::post('/', [LaporanKerusakanController::class, 'store'])->name('laporan.store');
		Route::get('/edit/{id}', [LaporanKerusakanController::class, 'edit']);
		Route::put('/update/{id}', [LaporanKerusakanController::class, 'update']);
		Route::delete('/delete/{id}', [LaporanKerusakanController::class, 'destroy']);
		Route::get('/get-ruangan/{id}', [LaporanKerusakanController::class, 'getRuangan']);
		Route::get('/get-fasilitas/{id}', [LaporanKerusakanController::class, 'getFasilitas']);
	});

	/** -----------------------------
	 *  Verifikasi Laporan (Untuk Sarpras verifikasi laporan)
	 *  ---------------------------- */
	Route::prefix('verifikasi')->group(function () {
		Route::get('/', [VerifikasiLaporanController::class, 'index'])->name('prioritas.index');
		Route::get('/true/{id}', [VerifikasiLaporanController::class, 'verif']);
		Route::post('/konfirm/{id}', [VerifikasiLaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
		Route::get('/false/{id}', [VerifikasiLaporanController::class, 'tolakForm']);
		Route::put('/tolak/{id}', [VerifikasiLaporanController::class, 'tolak'])->name('laporan.tolak');
	});

	/** -----------------------------
	 *  MABAC
	 *  ---------------------------- */
	Route::get('/mabac', [MabacController::class, 'index'])->name('mabac.index');

	/** -----------------------------
	 *  Perbaikan (Untuk Teknisi)
	 *  ---------------------------- */
	Route::prefix('perbaikan')->group(function () {
		Route::get('/', [PerbaikanController::class, 'index'])->name('perbaikan_teknisi.index');
		Route::get('/edit/{id}', [PerbaikanController::class, 'edit']);
		Route::put('/update/{id}', [PerbaikanController::class, 'update']);
		Route::get('/detail/{id}', [PerbaikanController::class, 'detail']);
	});

	/** -----------------------------
	 *  Verifikasi laporan perbaikan dan Penugasan Teknisi
	 *  ---------------------------- */
	Route::get('/laporan/penugasan/{id}', [LaporanKerusakanController::class, 'tugaskanTeknisi']);
	Route::post('/penugasan-teknisi', [LaporanKerusakanController::class, 'simpanPenugasan']);
	Route::get('/laporan/verifikasi/{id}', [LaporanKerusakanController::class, 'verifikasiPerbaikan']);
	Route::post('/verifikasi-perbaikan', [LaporanKerusakanController::class, 'simpanVerifikasi']);

	// Route untuk mengambil list data Ruangan dan Fasilitas yang digunakan untuk pilihan di form 
	Route::get('/get-ruangan/{id_gedung}', [LaporanKerusakanController::class, 'getRuangan']);
	Route::get('/get-fasilitas/{id_ruangan}', [LaporanKerusakanController::class, 'getFasilitas']);
});
