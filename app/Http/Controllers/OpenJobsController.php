<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpenJobsController extends Controller
{
    public function jobParameters()
    {
        return view('jobs_parameters');
    }

    public function appliedJobs()
    {
        return view('applied_jobs');
    }

    public function closedJobs()
    {
        return view('closed_jobs');
    }
}
