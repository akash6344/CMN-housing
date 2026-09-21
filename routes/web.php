<?php

use App\Http\Controllers\Builder\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/projects', [DashboardController::class, 'projects'])->name('builder.projects');
Route::get('/leads', [DashboardController::class, 'leads'])->name('builder.leads');
Route::get('/notifications', [DashboardController::class, 'notifications'])->name('builder.notifications');
Route::get('/settings', [DashboardController::class, 'settings'])->name('builder.settings');

Route::get('/listings', fn () => app(DashboardController::class)->comingSoon('listings'))->name('builder.listings');
Route::get('/bargain', fn () => app(DashboardController::class)->comingSoon('bargain'))->name('builder.bargain');
Route::get('/ads', fn () => app(DashboardController::class)->comingSoon('ads'))->name('builder.ads');
Route::get('/analytics', fn () => app(DashboardController::class)->comingSoon('analytics'))->name('builder.analytics');
Route::get('/documents', fn () => app(DashboardController::class)->comingSoon('documents'))->name('builder.documents');
