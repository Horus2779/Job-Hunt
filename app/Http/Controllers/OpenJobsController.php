<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppliedJobs;

class OpenJobsController extends Controller
{
    public function jobParameters()
    {
        return view('jobs_parameters');
    }

    public function appliedJobs()
    {
        $jobs = AppliedJobs::where('status', 1)->get();

        return view('applied_jobs', compact('jobs'));
    }

    public function closedJobs()
    {
        return view('closed_jobs');
    }
}
