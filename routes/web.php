<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpenJobsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);


Route::get('/jobs/parameters', [OpenJobsController::class, 'jobParameters'])->name('job_parameters');
Route::get('/jobs/applied', [OpenJobsController::class, 'appliedJobs'])->name('job_parameters');
Route::get('/jobs/closed', [OpenJobsController::class, 'closedJobs'])->name('job_parameters');