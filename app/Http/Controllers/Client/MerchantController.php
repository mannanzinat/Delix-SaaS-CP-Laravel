<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        return view('website.client.merchant-app.index');
    }
    public function details()
    {
        return view('website.client.merchant-app.details');
    }
}
