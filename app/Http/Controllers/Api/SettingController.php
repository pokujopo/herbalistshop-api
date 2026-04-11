<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return response()->json([
            'success' => true,
            'setting' => $setting ? [
                'store_name' => $setting->store_name,
                'store_email' => $setting->store_email,
                'store_phone' => $setting->store_phone,
                'store_address' => $setting->store_address,
                'facebook_url' => $setting->facebook_url,
                'instagram_url' => $setting->instagram_url,
                'twitter_url' => $setting->twitter_url,
                'logo' => $setting->logo ? asset('storage/' . $setting->logo) : null,
            ] : null,
        ]);
    }
}
