<?php

namespace App\Http\Controllers;

use App\Services\AdzumaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private $adzuma;

    public function __construct(
    )
    {
        $this->adzuma = new AdzumaService;

    }


    public function index()
    {
        $this->adzuma->getJobs();
        return view('home');
    }
}
