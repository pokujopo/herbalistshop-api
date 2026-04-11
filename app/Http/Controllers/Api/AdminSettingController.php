<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return response()->json([
            'success' => true,
            'setting' => $setting ? [
                ...$setting->toArray(),
                'logo' => $setting->logo ? asset('storage/' . $setting->logo) : null,
            ] : null,
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['nullable', 'string', 'max:255'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:255'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $setting = Setting::first() ?? new Setting();

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->fill($data);
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.',
            'setting' => [
                ...$setting->toArray(),
                'logo' => $setting->logo ? asset('storage/' . $setting->logo) : null,
            ],
        ]);
    }
}