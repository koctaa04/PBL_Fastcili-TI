<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\VerifikasiLaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenugasanTeknisiController;
use App\Http\Controllers\WaspasController;
use App\Http\Controllers\NotificationController;

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
		Route::get('/detail/{id}', [GedungController::class, 'detail']);
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
		Route::get('/import', [RuanganController::class, 'import'])->name('users.import');
		Route::post('/import_ajax', [RuanganController::class, 'import_ajax'])->name('users.import.ajax');
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
		Route::get('/get-ruangan/{id}', [FasilitasController::class, 'getRuangan'])->name('fasilitas.getRuangan');
		Route::get('/list', [FasilitasController::class, 'list'])->name('fasilitas.list');
		Route::get('/import', [FasilitasController::class, 'import'])->name('users.import');
		Route::post('/import_ajax', [FasilitasController::class, 'import_ajax'])->name('users.import.ajax');
	});


	/** -----------------------------
	 *  Laporan Kerusakan
	 *  ---------------------------- */
	Route::prefix('lapor_kerusakan')->group(function () {
		Route::get('/', [LaporanKerusakanController::class, 'index'])->name('perbaikan.index');
		Route::post('/', [LaporanKerusakanController::class, 'store'])->name('laporan.store');
		Route::delete('/delete/{id}', [LaporanKerusakanController::class, 'destroy'])->name('laporan.destroy');
		Route::get('/trending', [LaporanKerusakanController::class, 'trending'])->name('trending.index');
		Route::get('/penilaian/{id}', [LaporanKerusakanController::class, 'showPenilaian'])->name('penilaian.show');
		Route::post('/simpan-penilaian/{id}', [LaporanKerusakanController::class, 'simpanPenilaian'])->name('laporan.simpanPenilaian');
	});


	/** -----------------------------
	 *  Get Ruangan dan Fasilitas
	 *  ---------------------------- */
	Route::get('/get-ruangan/{idGedung}', [LaporanKerusakanController::class, 'getRuangan']);
	Route::get('/get-fasilitas-terlapor/{idRuangan}', [LaporanKerusakanController::class, 'getFasilitasTerlapor']);
	Route::get('/get-fasilitas-belum-lapor/{idRuangan}', [LaporanKerusakanController::class, 'getFasilitasBelumLapor']);



	/** -----------------------------
	 *  WASPAS
	 *  ---------------------------- */
	Route::get('/prioritas', [WaspasController::class, 'index'])->name('prioritas.index');

	/** -----------------------------
	 *  Perbaikan (Untuk Teknisi)
	 *  ---------------------------- */
	Route::prefix('perbaikan')->group(function () {
		Route::get('/', [PerbaikanController::class, 'index'])->name('perbaikan_teknisi.index');
		Route::get('/edit/{id}', [PerbaikanController::class, 'edit']);
		Route::put('/update/{id}', [PerbaikanController::class, 'update']);
		Route::get('/detail/{id}', [PerbaikanController::class, 'detail']);
	});

	Route::middleware(['authorize:1,3,4'])->group(function () {
		Route::get('/pelapor', [HomeController::class, 'pelapor'])->name('pelapor');
		Route::get('/pelapor/create', [LaporanKerusakanController::class, 'createPelapor'])->name('pelapor.create');
		Route::post('/', [LaporanKerusakanController::class, 'storePelapor'])->name('pelapor.store');
		Route::get('/edit/{id}', [LaporanKerusakanController::class, 'editPelapor'])->name('pelapor.edit');
		Route::put('/update/{id}', [LaporanKerusakanController::class, 'updatePelapor'])->name('pelapor.update');
		Route::get('/rate/{id}', [LaporanKerusakanController::class, 'rate'])->name('pelapor.rate');
		Route::put('/rating/{id}', [LaporanKerusakanController::class, 'rating'])->name('pelapor.rating');
		Route::get('/detail/{id}', [LaporanKerusakanController::class, 'detail'])->name('pelapor.detail');
		Route::delete('/delete/{id}', [LaporanKerusakanController::class, 'destroy'])->name('pelapor.delete');
		Route::get('/get-ruangan/{id}', [LaporanKerusakanController::class, 'getRuangan']);
		Route::get('/get-fasilitas/{id}', [LaporanKerusakanController::class, 'getFasilitas']);
	});

	Route::middleware(['authorize:1,3'])->group(function () {
		Route::get('/teknisi', [HomeController::class, 'teknisi'])->name('teknisi');
		Route::get('/feedback-teknisi/{id}', [PenugasanTeknisiController::class, 'feedback'])->name('teknisi.feedback');
		Route::put('/feedback-teknisi/{id}', [PenugasanTeknisiController::class, 'feedbackTeknisi'])->name('teknisi.feedbacksimpan');
		Route::get('/detail-riwayat/{id}', [PenugasanTeknisiController::class, 'detailRiwayat'])->name('teknisi.detailRiwayat');
	});
	/** -----------------------------
	 *  Verifikasi laporan perbaikan dan Penugasan Teknisi
	 *  ---------------------------- */
	Route::get('/laporan/penugasan/{id}', [LaporanKerusakanController::class, 'tugaskanTeknisi']);
	Route::post('/penugasan-teknisi', [LaporanKerusakanController::class, 'simpanPenugasan']);
	Route::get('/laporan/verifikasi/{id}', [LaporanKerusakanController::class, 'verifikasiPerbaikan']);
	Route::post('/verifikasi-perbaikan', [LaporanKerusakanController::class, 'simpanVerifikasi']);

    Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index'); // Optional: page to view all notifications
});
