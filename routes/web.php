<?php

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
    return view('Auth.auth-login');
});

//Pdf
Route::prefix('pdf')->name('pdf.')->group(function (){
    Route::get('goTocertificat',[\App\Http\Controllers\PdfController::class,'goTocertificat'])->name('goTocertificat');
});

//penalite_equipe
Route::prefix('penalite_equipe')->name('penalite_equipe.')->group(function (){
    Route::get('ressource',[\App\Http\Controllers\Penalite_equipeController::class,'insert'])->name('ressource');
    Route::post('insert',[\App\Http\Controllers\Penalite_equipeController::class,'create'])->name('insert');
    Route::post('modifier',[\App\Http\Controllers\Penalite_equipeController::class,'modifier'])->name('modifier');
    Route::get('delete/{id}', [\App\Http\Controllers\Penalite_equipeController::class, 'destroy'])->name('destroy');
});

//Import
Route::prefix('import')->name('import.')->group(function () {
    Route::get('goToImportResultat', [\App\Http\Controllers\ImportController::class, 'goToImportResultat'])->name('goToImportResultat');
    Route::get('goToImportPoint', [\App\Http\Controllers\ImportController::class, 'goToImportPoint'])->name('goToImportPoint');
    Route::post('importEtapeResultat', [\App\Http\Controllers\ImportController::class, 'importEtapeResultat'])->name('importEtapeResultat');
    Route::post('importPoint', [\App\Http\Controllers\ImportController::class, 'importPoint'])->name('importPoint');
});

//Classement
Route::prefix('classement')->name('classement.')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('coureurEtape/', [\App\Http\Controllers\ClassementController::class, 'ClassementcoureurEtape'])->name('coureurEtape');
        Route::get('classementParEquipe/', [\App\Http\Controllers\ClassementController::class, 'ClassementParEquipe'])->name('classementParEquipe');
        Route::get('getDetailClassement/{idEquipe}', [\App\Http\Controllers\ClassementController::class, 'getDetailClassement'])->name('getDetailClassement');
        Route::get('getEtapeAdmin', [\App\Http\Controllers\ClassementController::class, 'getEtapeAdmin'])->name('getEtapeAdmin');
    });
    Route::prefix('equipe')->name('equipe.')->group(function () {
        Route::get('coureurEtape/', [\App\Http\Controllers\ClassementController::class, 'ClassementcoureurEtapeClient'])->name('coureurEtape');
        Route::get('classementParEquipe/', [\App\Http\Controllers\ClassementController::class, 'ClassementParEquipeClient'])->name('classementParEquipe');
        Route::get('getEtapeClient/', [\App\Http\Controllers\ClassementController::class, 'getEtapeClient'])->name('getEtapeClient');
    });
});
//Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('truncate', [\App\Http\Controllers\AdminController::class, 'truncate'])->name('truncate');
    Route::get('acceuil', [\App\Http\Controllers\AdminController::class, 'goToAcceuil'])->name('acceuil');
    Route::get('ajoutTempCoureur/{idEtape}', [\App\Http\Controllers\AdminController::class, 'goToAjoutTempCoureur'])->name('ajoutTempCoureur');
    Route::post('insertTempCoureur', [\App\Http\Controllers\AdminController::class, 'insertTempCoureur'])->name('insertTempCoureur');
    Route::get('goTocategorie', [\App\Http\Controllers\AdminController::class, 'goToCategorie'])->name('goTocategorie');
    Route::get('generertCategorie', [\App\Http\Controllers\AdminController::class, 'genererCategorie'])->name('generertCategorie');
});

//Equipe
Route::prefix('equipe')->name('equipe.')->group(function () {
    Route::get('login', [\App\Http\Controllers\EquipeController::class, 'goToLogin'])->name('login');
    Route::get('accueil', [\App\Http\Controllers\EquipeController::class, 'goToAcceuil'])->name('accueil');
    Route::get('ajoutCoureur/{idEtape}', [\App\Http\Controllers\EquipeController::class, 'goToAjoutCoureur'])->name('ajoutCoureur');
    Route::get('insertCoureurEtape', [\App\Http\Controllers\EquipeController::class, 'insertCoureurEtape'])->name('insertCoureurEtape');
});

//Auth
Route::post('Auth/Login',[\App\Http\Controllers\AuthController::class,'Login']);
Route::post('Auth/LoginEquipe',[\App\Http\Controllers\AuthController::class,'LoginEquipe']);

//Log Out
Route::get('LogOut/all',[\App\Http\Controllers\AuthController::class,'logOut']);
Route::get('LogOut/equipe',[\App\Http\Controllers\AuthController::class,'logOutEquipe']);
