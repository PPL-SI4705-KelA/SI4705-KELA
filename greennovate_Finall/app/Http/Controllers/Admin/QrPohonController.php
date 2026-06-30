<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QrPohonController extends Controller
{
    /**
     * GET /admin/qr-pohon
     * Halaman generate QR Code untuk pohon (admin).
     */
    public function index()
    {
        return view('admin.qr-pohon.index');
    }
}
