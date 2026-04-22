<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = $request->except(['_token', 'facebook_link', 'instagram_link', 'whatsapp_link', 'tiktok_link']);

        // Handle Social Links specially
        if ($request->hasAny(['facebook_link', 'instagram_link', 'whatsapp_link', 'tiktok_link'])) {
            $socialLinks = Setting::where('key', 'social_links')->first();
            if ($socialLinks) {
                $links = [
                    'facebook' => $request->facebook_link,
                    'instagram' => $request->instagram_link,
                    'whatsapp' => $request->whatsapp_link,
                    'tiktok' => $request->tiktok_link,
                ];
                $socialLinks->update(['value' => json_encode($links)]);
            }
        }

        foreach ($keys as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) continue;

            if ($setting->type === 'image') {
                if ($request->hasFile($key)) {
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('settings', 'public');
                    $setting->update(['value' => $path]);
                }
            } elseif ($setting->type === 'image_gallery') {
                if ($request->hasFile($key)) {
                    $gallery = json_decode($setting->value, true) ?: [];
                    foreach ($request->file($key) as $file) {
                        $path = $file->store('settings/gallery', 'public');
                        $gallery[] = $path;
                    }
                    $setting->update(['value' => json_encode($gallery)]);
                }
            } else {
                $setting->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Master settings updated successfully.');
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'image' => 'required|string'
        ]);

        $setting = Setting::where('key', $request->key)->first();
        if (!$setting) return back()->with('error', 'Setting not found.');

        if ($setting->type === 'image_gallery') {
            $gallery = json_decode($setting->value, true) ?: [];
            if (($key = array_search($request->image, $gallery)) !== false) {
                unset($gallery[$key]);
                Storage::disk('public')->delete($request->image);
                $setting->update(['value' => json_encode(array_values($gallery))]);
            }
        } elseif ($setting->type === 'image') {
            if ($setting->value === $request->image) {
                Storage::disk('public')->delete($request->image);
                $setting->update(['value' => null]);
            }
        }

        return back()->with('success', 'Image removed from protocol.');
    }
}
