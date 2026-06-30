<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;

class AdminQrCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::latest()->get();
        return view('admin.qrcode.index', compact('qrCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        QrCode::create($request->only('judul', 'link'));

        return redirect()->route('admin.qrcode.index')->with('success', 'QR Code berhasil dibuat!');
    }

    public function destroy(QrCode $qrcode)
    {
        $qrcode->delete();
        return redirect()->route('admin.qrcode.index')->with('success', 'QR Code berhasil dihapus!');
    }
}
