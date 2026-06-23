<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')->get();
       
        return response()->json($banners);
    }
}
