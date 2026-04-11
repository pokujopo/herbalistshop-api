<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function check(){
        return view('check');
    }
}
