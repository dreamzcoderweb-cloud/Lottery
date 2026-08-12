<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Fetch admin panel setting details (QR image, theme color, etc.)
     */
    public function index(): JsonResponse
    {
        $qrImage = Setting::get('qr_image');
        $themeColor = Setting::get('theme_color', '#696cff');

        $qrImageUrl = null;
        if (!empty($qrImage)) {
            $qrImageUrl = url('assets/img/qr_code/' . $qrImage);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Setting details fetched successfully',
            'data'    => [
                'qr_image'     => $qrImage,
                'qr_image_url' => $qrImageUrl,
                'theme_color'  => $themeColor,
            ],
        ]);
    }
}
