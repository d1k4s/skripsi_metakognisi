<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\backend\AbsensiGuruController;
use App\Http\Controllers\backend\AbsensiSiswaController;
use App\Http\Controllers\backend\ELearningController;
use App\Http\Controllers\backend\GuruController;
use App\Http\Controllers\backend\JawabanController;
use App\Http\Controllers\backend\JenjangController;
use App\Http\Controllers\backend\JurusanController;
use App\Http\Controllers\backend\KelasController;
use App\Http\Controllers\backend\MataPelajaranController;
use App\Http\Controllers\backend\NilaiController;
use App\Http\Controllers\backend\RaportController;
use App\Http\Controllers\backend\SiswaController;
use App\Http\Controllers\backend\SoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\frontend\AboutController;
use App\Http\Controllers\frontend\BlogController;
use App\Http\Controllers\frontend\ContactController;
use App\Http\Controllers\frontend\CourseController;
use App\Http\Controllers\frontend\EventController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home');
});

// frontend
Route::get('about', [AboutController::class, 'index'])->name('about.frontend');
Route::get('course', [CourseController::class, 'index'])->name('course.frontend');
Route::get('event', [EventController::class, 'index'])->name('event.frontend');
Route::get('blog', [BlogController::class, 'index'])->name('blog.frontend');
Route::get('get-contact', [ContactController::class, 'index'])->name('contact.frontend');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/logouts', [LoginController::class, 'logout'])->name('logouts');

    Route::resource('jenjang', JenjangController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('mapel', MataPelajaranController::class);
    Route::resource('kelas_admin', KelasController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('e_learning', ELearningController::class);
    Route::resource('soal', SoalController::class);
    Route::resource('jawaban', JawabanController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('nilai', NilaiController::class);
    Route::resource('raports', RaportController::class);
    Route::resource('absensi-guru', AbsensiGuruController::class);
    Route::resource('absensi_siswa', AbsensiSiswaController::class);

    // Survey export routes
    Route::get('/survey-export', [SurveyController::class, 'exportIndex'])->name('survey.export.index');
    Route::get('/survey-export/download', [SurveyController::class, 'exportCsv'])->name('survey.export.csv');
    Route::get('/survey-export/detail/{id}', [SurveyController::class, 'showDetail'])->name('survey.export.detail');

    // Simple download route for survey data
});

Route::get('/download-survey-data', [SurveyController::class, 'downloadExcel'])->name('survey.download.excel');
// Survey Routes - Fixed route definitions
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::post('/survey/save-data', [SurveyController::class, 'saveDataDiri'])->name('survey.save-data');
Route::get('/survey/questions/{step}', [SurveyController::class, 'showQuestions'])->name('survey.questions');
Route::post('/survey/questions/{step}', [SurveyController::class, 'saveQuestions'])->name('survey.save-questions');
Route::get('/survey/thank-you', [SurveyController::class, 'thankYou'])->name('survey.thankyou');

Auth::routes();
