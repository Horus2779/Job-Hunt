<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpenJobsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/jobs/parameters', [OpenJobsController::class, 'jobParameters'])->name('job_parameters');
Route::get('/jobs/applied', [OpenJobsController::class, 'appliedJobs'])->name('appliedJobs');
Route::get('/jobs/closed', [OpenJobsController::class, 'closedJobs'])->name('closedJobs');

Route::get('/sites/adzuna', [OpenJobsController::class, 'closedJobs'])->name('adzuna');
