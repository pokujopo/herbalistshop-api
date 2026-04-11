<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;

class AdminNewsletterController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Newsletter::latest()->paginate(30),
        ]);
    }
}
