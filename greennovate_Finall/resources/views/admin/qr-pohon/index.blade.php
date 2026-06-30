@extends('layouts.admin')

@section('title', 'QR Code Pohon – Admin Greennovate')
@section('page-title', 'QR Code Pohon')
@section('page-subtitle', 'Generate QR Code dari link untuk ditempelkan pada pohon')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Hero Card --}}
    <div class="bg-gradient-to-br from-[#0D8B41] to-[#064d24] rounded-2xl p-6 text-white flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 bg-white/15 rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold">Generate QR Code Pohon</h2>
            <p class="text-white/75 text-sm mt-0.5">Masukkan link detail pohon, lalu cetak QR Code untuk ditempelkan pada papan nama pohon.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Form Generator --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="w-6 h-6 bg-green-100 rounded-md flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-[#0D8B41]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </span>
                Input Link
            </h3>

            <div class="space-y-4">
                {{-- URL Input --}}
                <div>
                    <label for="qr-url" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        URL / Link Pohon <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="qr-url"
                           placeholder="https://contoh.com/detail-pohon/123"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent transition placeholder-gray-400">
                    <p class="text-xs text-gray-400 mt-1.5">Masukkan URL lengkap termasuk https://</p>
                </div>

                {{-- Label --}}
                <div>
                    <label for="qr-label" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Label / Nama Pohon <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text"
                           id="qr-label"
                           placeholder="cth: Pohon Trembesi – Blok A-12"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent transition placeholder-gray-400">
                </div>

                {{-- Ukuran --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ukuran QR Code</label>
                    <div class="flex gap-2">
                        <button type="button" id="btn-sm" onclick="setSize(200)"
                            class="size-btn active flex-1 py-2 text-sm font-semibold rounded-xl transition">
                            Kecil (200px)
                        </button>
                        <button type="button" id="btn-md" onclick="setSize(300)"
                            class="size-btn flex-1 py-2 text-sm font-semibold rounded-xl transition">
                            Sedang (300px)
                        </button>
                        <button type="button" id="btn-lg" onclick="setSize(400)"
                            class="size-btn flex-1 py-2 text-sm font-semibold rounded-xl transition">
                            Besar (400px)
                        </button>
                    </div>
                </div>

                {{-- Tombol Generate --}}
                <button type="button" id="btn-generate" onclick="generateQR()"
                    class="w-full py-3 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-bold rounded-xl transition text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span id="btn-generate-text">Generate QR Code</span>
                </button>
            </div>

            {{-- Error state --}}
            <div id="qr-error" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="qr-error-text">URL tidak valid.</span>
            </div>
        </div>

        {{-- QR Code Result --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col items-center justify-center min-h-[360px]">

            {{-- Placeholder --}}
            <div id="qr-placeholder" class="text-center">
                <div class="w-32 h-32 mx-auto border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <p class="text-gray-400 text-sm font-medium">QR Code akan muncul di sini</p>
                <p class="text-gray-300 text-xs mt-1">Masukkan URL dan klik Generate</p>
            </div>

            {{-- Loading --}}
            <div id="qr-loading" class="hidden text-center">
                <div class="w-16 h-16 border-4 border-green-200 border-t-green-500 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-400 text-sm">Membuat QR Code...</p>
            </div>

            {{-- QR Result --}}
            <div id="qr-output" class="hidden w-full flex-col items-center text-center">

                {{-- Label --}}
                <p id="qr-label-display" class="text-sm font-bold text-gray-800 mb-3"></p>

                {{-- QR Image (dari API) --}}
                <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl shadow-inner inline-block">
                    <img id="qr-img" src="" alt="QR Code" class="block rounded-lg">
                </div>

                {{-- URL Display --}}
                <div class="mt-3 w-full max-w-xs">
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <p id="qr-url-display" class="text-xs text-gray-500 truncate flex-1 text-left"></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 mt-4 w-full max-w-xs">
                    <a id="btn-download" href="#" download="qr-pohon.png" target="_blank"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-semibold text-sm rounded-xl transition shadow-sm active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PNG
                    </a>
                    <button type="button" onclick="printQR()"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- History --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">Riwayat Generate (Sesi Ini)</h3>
            <button type="button" onclick="clearHistory()" class="text-xs text-gray-400 hover:text-red-500 transition font-medium">Hapus Semua</button>
        </div>
        <div id="history-list" class="divide-y divide-gray-50">
            <div id="history-empty" class="px-6 py-8 text-center">
                <p class="text-gray-400 text-sm">Belum ada QR Code yang digenerate.</p>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<style>
    .size-btn { border: 2px solid #e5e7eb; color: #6b7280; }
    .size-btn.active { border-color: #16a34a; background: #f0fdf4; color: #15803d; }
    .size-btn:hover:not(.active) { border-color: #d1d5db; }
</style>

<script>
    // ── State ─────────────────────────────────────────────────────────────
    var currentSize  = 200;
    var qrHistory    = [];
    var currentApiUrl = '';

    // ── Ukuran ────────────────────────────────────────────────────────────
    function setSize(size) {
        currentSize = size;
        document.querySelectorAll('.size-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        var map = { 200: 'btn-sm', 300: 'btn-md', 400: 'btn-lg' };
        document.getElementById(map[size]).classList.add('active');
    }

    // ── Generate QR ───────────────────────────────────────────────────────
    function generateQR() {
        var urlInput = document.getElementById('qr-url');
        var url      = urlInput.value.trim();
        var label    = document.getElementById('qr-label').value.trim();
        var errBox   = document.getElementById('qr-error');
        var errText  = document.getElementById('qr-error-text');

        // Sembunyikan error sebelumnya
        errBox.classList.add('hidden');

        // Validasi
        if (!url) {
            errText.textContent = 'URL tidak boleh kosong.';
            errBox.classList.remove('hidden');
            return;
        }
        if (!/^https?:\/\/.+/.test(url)) {
            errText.textContent = 'URL tidak valid. Pastikan dimulai dengan https:// atau http://';
            errBox.classList.remove('hidden');
            return;
        }

        // Tampilkan loading, sembunyikan yang lain
        document.getElementById('qr-placeholder').classList.add('hidden');
        document.getElementById('qr-output').classList.add('hidden');
        document.getElementById('qr-output').classList.remove('flex');
        document.getElementById('qr-loading').classList.remove('hidden');

        // Ubah tombol ke loading state
        var btn     = document.getElementById('btn-generate');
        var btnText = document.getElementById('btn-generate-text');
        btn.disabled       = true;
        btn.classList.add('opacity-75', 'cursor-wait');
        btnText.textContent = 'Membuat QR...';

        // Build API URL (qrserver.com – gratis, no API key)
        var apiUrl = 'https://api.qrserver.com/v1/create-qr-code/'
            + '?size=' + currentSize + 'x' + currentSize
            + '&data=' + encodeURIComponent(url)
            + '&color=064d24'
            + '&bgcolor=ffffff'
            + '&format=png'
            + '&ecc=H'
            + '&margin=2';

        currentApiUrl = apiUrl;

        // Buat img element baru agar load event terpicu
        var img      = document.getElementById('qr-img');
        img.onload   = function() { onQrLoaded(url, label, apiUrl); };
        img.onerror  = function() { onQrError(); };
        img.src      = apiUrl;
    }

    function onQrLoaded(url, label, apiUrl) {
        // Reset tombol
        var btn     = document.getElementById('btn-generate');
        var btnText = document.getElementById('btn-generate-text');
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-wait');
        btnText.textContent = 'Generate QR Code';

        // Sembunyikan loading
        document.getElementById('qr-loading').classList.add('hidden');

        // Set ukuran gambar
        var img = document.getElementById('qr-img');
        img.width  = currentSize;
        img.height = currentSize;
        img.style.width  = currentSize + 'px';
        img.style.height = currentSize + 'px';

        // Tampilkan output
        var output = document.getElementById('qr-output');
        output.classList.remove('hidden');
        output.classList.add('flex');

        // Label
        var labelEl = document.getElementById('qr-label-display');
        if (label) {
            labelEl.textContent = label;
            labelEl.classList.remove('hidden');
        } else {
            labelEl.textContent = '';
            labelEl.classList.add('hidden');
        }

        // URL display
        document.getElementById('qr-url-display').textContent = url;

        // Download link – arahkan ke API URL langsung
        var dlBtn = document.getElementById('btn-download');
        dlBtn.href     = apiUrl;
        dlBtn.download = 'qr-pohon-' + (label || Date.now()) + '.png';

        // Tambah ke history
        addToHistory(url, label, apiUrl);
    }

    function onQrError() {
        var btn     = document.getElementById('btn-generate');
        var btnText = document.getElementById('btn-generate-text');
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-wait');
        btnText.textContent = 'Generate QR Code';

        document.getElementById('qr-loading').classList.add('hidden');
        document.getElementById('qr-placeholder').classList.remove('hidden');

        var errText = document.getElementById('qr-error-text');
        errText.textContent = 'Gagal membuat QR Code. Periksa koneksi internet dan coba lagi.';
        document.getElementById('qr-error').classList.remove('hidden');
    }

    // ── Print QR ──────────────────────────────────────────────────────────
    function printQR() {
        var img   = document.getElementById('qr-img').src;
        var label = document.getElementById('qr-label').value.trim();
        var url   = document.getElementById('qr-url').value.trim();

        var w = window.open('', '_blank', 'width=600,height=700');
        w.document.write(
            '<!DOCTYPE html><html><head><title>QR Code Pohon</title><style>'
            + 'body{font-family:Segoe UI,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#fff}'
            + '.box{text-align:center;padding:40px;border:2px solid #e5e7eb;border-radius:20px;max-width:380px}'
            + '.logo{font-size:22px;font-weight:800;color:#0D8B41;margin-bottom:24px}'
            + 'img{border:6px solid #f3f4f6;border-radius:16px}'
            + '.lbl{font-size:16px;font-weight:700;color:#111;margin-top:20px}'
            + '.url{font-size:11px;color:#6b7280;margin-top:8px;word-break:break-all}'
            + '.hint{font-size:12px;color:#9ca3af;margin-top:16px}'
            + '@media print{body{print-color-adjust:exact}}'
            + '</style></head><body>'
            + '<div class="box">'
            + '<div class="logo">🌿 Greennovate</div>'
            + '<img src="' + img + '" width="240" height="240">'
            + (label ? '<div class="lbl">' + label + '</div>' : '')
            + '<div class="url">' + url + '</div>'
            + '<div class="hint">Scan QR Code ini untuk detail pohon</div>'
            + '</div>'
            + '<script>window.onload=function(){window.print();}<\/script>'
            + '</body></html>'
        );
        w.document.close();
    }

    // ── History ───────────────────────────────────────────────────────────
    function addToHistory(url, label, apiUrl) {
        qrHistory.unshift({ url: url, label: label, apiUrl: apiUrl, time: new Date() });
        if (qrHistory.length > 10) qrHistory.pop();
        renderHistory();
    }

    function renderHistory() {
        var list  = document.getElementById('history-list');
        var empty = document.getElementById('history-empty');

        list.querySelectorAll('.hist-item').forEach(function(el) { el.remove(); });

        if (qrHistory.length === 0) {
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        qrHistory.forEach(function(item, i) {
            var div       = document.createElement('div');
            div.className = 'hist-item flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition cursor-pointer';
            div.onclick   = function() { loadFromHistory(i); };

            var time = item.time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            div.innerHTML =
                '<img src="' + item.apiUrl + '" class="w-10 h-10 rounded-lg border border-gray-200 flex-shrink-0" alt="QR">'
                + '<div class="flex-1 min-w-0">'
                +   '<p class="text-sm font-semibold text-gray-800 truncate">' + (item.label || item.url) + '</p>'
                +   '<p class="text-xs text-gray-400 truncate">' + item.url + '</p>'
                + '</div>'
                + '<span class="text-xs text-gray-300 flex-shrink-0">' + time + '</span>';

            list.appendChild(div);
        });
    }

    function loadFromHistory(i) {
        var item = qrHistory[i];
        document.getElementById('qr-url').value   = item.url;
        document.getElementById('qr-label').value = item.label || '';
        generateQR();
    }

    function clearHistory() {
        qrHistory = [];
        renderHistory();
    }

    // ── Enter key support ─────────────────────────────────────────────────
    document.getElementById('qr-url').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') generateQR();
    });
</script>
@endpush
