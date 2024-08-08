<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function index()
    {
        return view('website.client.rider-app.index');
    }
    public function details()
    {
        return view('website.client.rider-app.details');
    }
}
