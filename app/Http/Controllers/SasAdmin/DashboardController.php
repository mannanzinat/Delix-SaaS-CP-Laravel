<?php

namespace App\Http\Controllers\SASAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('sas.dashboard');
    }
}
