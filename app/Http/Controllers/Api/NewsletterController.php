<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // prevent duplicate
        $exists = Newsletter::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Email hii tayari ipo kwenye list.',
            ], 409);
        }

        Newsletter::create([
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Umejiunga kikamilifu 🎉',
        ]);
    }
}