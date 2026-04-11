<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class azampayController extends Controller
{
    public function azampay(){
        return view('customer.payment');
    }

    public function callback(){
        return view('customer.callback');
    }
}
