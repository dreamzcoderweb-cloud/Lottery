<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $qrImage = Setting::get('qr_image');
        $themeColor = Setting::get('theme_color', '#696cff');

        return view('settings.index', compact('qrImage', 'themeColor'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'qr_image'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]);

        if ($request->hasFile('qr_image')) {
            $image = $request->file('qr_image');
            $imageName = 'qr_code_' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/img/qr_code');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $imageName);
            Setting::set('qr_image', $imageName);
        }

        Setting::set('theme_color', $request->input('theme_color'));

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
