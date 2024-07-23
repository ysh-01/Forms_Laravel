<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Form routes
Route::middleware(['auth'])->group(function () {
    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
    Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{form}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form}', [FormController::class, 'destroy'])->name('forms.destroy');
    Route::get('/forms/{form}/preview', [FormController::class, 'preview'])->name('forms.preview');
    Route::patch('/forms/{form}/publish', [FormController::class, 'togglePublish'])->name('forms.publish');
    Route::get('/forms/template/{template}', [FormController::class, 'createWithTemplate'])->name('forms.template');

});

// Response routes
Route::middleware(['auth'])->group(function () {
    Route::get('/forms/respond/{form}', [ResponseController::class, 'showForm'])->name('responses.showForm');
    Route::post('/forms/respond/{form}', [ResponseController::class, 'submitForm'])->name('responses.submitForm');
    Route::get('/forms/{form}/responses', [ResponseController::class, 'viewResponses'])->name('responses.viewResponses');
    Route::get('/forms/{form}/success', [ResponseController::class, 'showSuccess'])->name('responses.success');
    Route::get('/forms/{form}/responses/{responseId}', [ResponseController::class, 'viewResponse'])->name('responses.viewResponse');
});
