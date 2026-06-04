<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrScanController extends Controller
{
    /**
     * GET /qr-scan
     * Halaman scan QR Code (user).
     */
    public function index()
    {
        return view('qr-scan.index');
    }
}
