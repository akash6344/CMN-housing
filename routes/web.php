<?php

use App\Http\Controllers\Builder\DashboardController;
use App\Http\Controllers\Builder\ProjectWizardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/projects', [DashboardController::class, 'projects'])->name('builder.projects');
Route::get('/projects/new', [DashboardController::class, 'postProject'])->name('builder.projects.create');
Route::get('/projects/preview', [DashboardController::class, 'projectPreview'])->name('builder.projects.preview');
Route::get('/leads', [DashboardController::class, 'leads'])->name('builder.leads');
Route::get('/listings', [DashboardController::class, 'listings'])->name('builder.listings');
Route::get('/ads', [DashboardController::class, 'ads'])->name('builder.ads');
Route::get('/analytics', [DashboardController::class, 'analytics'])->name('builder.analytics');
Route::get('/documents', [DashboardController::class, 'documents'])->name('builder.documents');
Route::get('/notifications', [DashboardController::class, 'notifications'])->name('builder.notifications');
Route::get('/settings', [DashboardController::class, 'settings'])->name('builder.settings');

Route::get('/bargain', fn () => app(DashboardController::class)->comingSoon('bargain'))->name('builder.bargain');

// Wizard Save Endpoints (Step 1 Basic, Step 2 Units, Step 3 Amenities, Draft)
Route::prefix('builder/projects/wizard')->name('builder.projects.wizard.')->group(function () {
    Route::post('/basic', [ProjectWizardController::class, 'saveBasicDetails'])->name('basic');
    Route::post('/units', [ProjectWizardController::class, 'saveUnits'])->name('units');
    Route::post('/amenities', [ProjectWizardController::class, 'saveAmenities'])->name('amenities');
    Route::post('/draft', [ProjectWizardController::class, 'saveDraft'])->name('draft');
    Route::get('/{project}', [ProjectWizardController::class, 'show'])->name('show');
});

