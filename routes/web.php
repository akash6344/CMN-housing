<?php

use App\Http\Controllers\Builder\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/projects', fn () => app(DashboardController::class)->comingSoon('projects'))->name('builder.projects');
Route::get('/listings', fn () => app(DashboardController::class)->comingSoon('listings'))->name('builder.listings');
Route::get('/leads', fn () => app(DashboardController::class)->comingSoon('leads'))->name('builder.leads');
Route::get('/bargain', fn () => app(DashboardController::class)->comingSoon('bargain'))->name('builder.bargain');
Route::get('/ads', fn () => app(DashboardController::class)->comingSoon('ads'))->name('builder.ads');
Route::get('/analytics', fn () => app(DashboardController::class)->comingSoon('analytics'))->name('builder.analytics');
Route::get('/documents', fn () => app(DashboardController::class)->comingSoon('documents'))->name('builder.documents');
Route::get('/notifications', fn () => app(DashboardController::class)->comingSoon('notifications'))->name('builder.notifications');
Route::get('/settings', fn () => app(DashboardController::class)->comingSoon('settings'))->name('builder.settings');
