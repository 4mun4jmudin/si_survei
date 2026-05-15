<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/search-siswa', function (\Illuminate\Http\Request $request) {
    $q = $request->query('q');
    if (strlen($q) < 2) return response()->json([]);

    $siswas = \App\Models\Siswa::where('nama_lengkap', 'like', "%$q%")
        ->orWhere('nis', 'like', "%$q%")
        ->limit(8)
        ->get(['nis', 'nama_lengkap', 'kelas']);

    return response()->json($siswas);
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->role === 'guru') {
        return redirect()->route('guru.dashboard');
    }
    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Portal Siswa
Route::middleware(['auth', 'role:siswa'])->name('siswa.')->group(function () {
    Route::get('/siswa', [App\Http\Controllers\SiswaController::class, 'index'])->name('dashboard');
    Route::get('/survei/{kuesioner}/detail', [App\Http\Controllers\SiswaController::class, 'showDetail'])->name('survei.detail');
    Route::get('/survei/{kuesioner}/mulai', [App\Http\Controllers\SiswaController::class, 'showSurvei'])->name('survei.show');
    Route::post('/survei/{kuesioner}', [App\Http\Controllers\SiswaController::class, 'simpanSurvei'])->name('survei.store');
    Route::get('/siswa/profil', [App\Http\Controllers\SiswaController::class, 'profil'])->name('profil');
    Route::put('/siswa/profil/password', [App\Http\Controllers\SiswaController::class, 'updatePassword'])->name('profil.password');
});

// Portal Guru
Route::middleware(['auth', 'role:guru'])->name('guru.')->group(function () {
    Route::get('/guru/portal', [App\Http\Controllers\GuruPortalController::class, 'index'])->name('dashboard');
    Route::get('/guru/survei/{kuesioner}/detail', [App\Http\Controllers\GuruPortalController::class, 'showDetail'])->name('survei.detail');
    Route::get('/guru/survei/{kuesioner}/mulai', [App\Http\Controllers\GuruPortalController::class, 'showSurvei'])->name('survei.show');
    Route::post('/guru/survei/{kuesioner}', [App\Http\Controllers\GuruPortalController::class, 'simpanSurvei'])->name('survei.store');
    Route::get('/guru/profil', [App\Http\Controllers\GuruPortalController::class, 'profil'])->name('profil');
    Route::put('/guru/profil/password', [App\Http\Controllers\GuruPortalController::class, 'updatePassword'])->name('profil.password');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Siswa
    Route::post('/siswa/import', [Admin\SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', Admin\SiswaController::class)->except(['show']);

    // Guru
    Route::post('/guru/import', [Admin\GuruController::class, 'import'])->name('guru.import');
    Route::resource('guru', Admin\GuruController::class)->except(['show']);

    // Kuesioner
    Route::resource('kuesioner', Admin\KuesionerController::class)->except(['show']);

    // Pertanyaan
    Route::get('/pertanyaan/next-order/{kuesioner}', [Admin\PertanyaanController::class, 'getNextOrder'])->name('pertanyaan.next-order');
    Route::resource('pertanyaan', Admin\PertanyaanController::class)->except(['show']);

    // Pengaturan
    Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    // Laporan
    Route::resource('laporan', Admin\LaporanController::class)->except(['show', 'edit', 'update']);
    Route::get('/laporan/{laporan}/cetak', [Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');

    // Jawaban
    Route::get('/jawaban', [Admin\JawabanController::class, 'index'])->name('jawaban.index');
    Route::get('/jawaban/{kuesioner}/{user}', [Admin\JawabanController::class, 'show'])->name('jawaban.show');
    Route::delete('/jawaban/{kuesioner}/{user}/reset', [Admin\JawabanController::class, 'reset'])->name('jawaban.reset');

    // Hasil Survei
    Route::get('/hasil-survei', [Admin\HasilSurveiController::class, 'index'])->name('hasil-survei.index');
    Route::get('/hasil-survei/{kuesioner}/export', [Admin\HasilSurveiController::class, 'exportExcel'])->name('hasil-survei.export');

    // Clustering K-Means
    Route::get('/clustering', [Admin\ClusteringController::class, 'index'])->name('clustering.index');

    // Profil Admin
    Route::get('/profil', [Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [Admin\ProfilController::class, 'updatePassword'])->name('profil.password');
});

require __DIR__.'/auth.php';
