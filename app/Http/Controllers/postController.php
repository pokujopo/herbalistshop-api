<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class postController extends Controller
{
    public function post(){
        return view('customer_care.post_product');
    }

}
