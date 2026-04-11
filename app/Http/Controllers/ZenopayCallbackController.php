<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZenopayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Zenopay Callback:', $request->all());

        // Hapa hifadhi status kwenye database
        // mfano: success / failed

        return response()->json(["status" => "received"]);
    }
}
