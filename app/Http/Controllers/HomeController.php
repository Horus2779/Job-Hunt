<?php

namespace App\Http\Controllers;

use App\Services\AdzumaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct(
    )
    {}


    public function index()
    {
        return view('home');
    }
}
