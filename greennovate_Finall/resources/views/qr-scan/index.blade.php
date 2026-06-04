@extends('layouts.auth')

@section('title', 'Scan QR Code Pohon – Greennovate')

@section('content')

<div class="w-full max-w-lg px-4 pb-16 mt-4">

    {{-- Header --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-[#0D8B41] to-[#064d24] rounded-2xl shadow-lg mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1-1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Scan QR Code Pohon</h1>
        <p class="text-gray-500 text-sm mt-1">Arahkan kamera ke QR Code pada papan nama pohon</p>
    </div>

    {{-- Scanner Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Camera Viewfinder --}}
        <div class="relative bg-gray-900" style="aspect-ratio: 1/1; max-height: 380px;">
            {{-- Scanner container --}}
            <div id="qr-reader" class="w-full h-full"></div>

            {{-- Overlay frame --}}
            <div id="scan-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                {{-- Corner frame --}}
                <div class="relative w-52 h-52">
                    {{-- Top-left --}}
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-lg"></div>
                    {{-- Top-right --}}
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-lg"></div>
                    {{-- Bottom-left --}}
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-lg"></div>
                    {{-- Bottom-right --}}
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-lg"></div>
                    {{-- Scan line animation --}}
                    <div id="scan-line" class="absolute left-2 right-2 h-0.5 bg-gradient-to-r from-transparent via-green-400 to-transparent top-0"></div>
                </div>
            </div>

            {{-- Permission / Init state --}}
            <div id="cam-init" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white text-center px-6">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="font-semibold text-lg mb-1">Akses Kamera</p>
                <p class="text-white/60 text-sm mb-5">Klik tombol di bawah untuk mengaktifkan kamera dan mulai scan</p>
                <button onclick="startScanner()" id="btn-start"
                    class="px-6 py-3 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-bold rounded-xl transition text-sm flex items-center gap-2 shadow-lg active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Mulai Scan
                </button>
            </div>

            {{-- Success overlay --}}
            <div id="scan-success-overlay" class="absolute inset-0 bg-green-900/90 flex flex-col items-center justify-center hidden">
                <div class="w-16 h-16 rounded-full bg-green-400 flex items-center justify-center mb-3 animate-bounce">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-lg">QR Code Terdeteksi!</p>
                <p class="text-green-200 text-sm mt-1">Membuka link di tab baru...</p>
            </div>
        </div>

        {{-- Status Bar --}}
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span id="status-dot" class="w-2 h-2 rounded-full bg-gray-300"></span>
                <span id="status-text" class="text-sm text-gray-500 font-medium">Kamera belum aktif</span>
            </div>
            <div class="flex gap-2" id="scanner-controls" style="display:none!important">
                <button onclick="stopScanner()" id="btn-stop"
                    class="text-xs font-semibold text-red-500 hover:text-red-700 px-3 py-1 border border-red-200 hover:border-red-300 rounded-lg transition">
                    Hentikan
                </button>
            </div>
        </div>

        {{-- Result Panel --}}
        <div class="p-5">

            {{-- Idle hint --}}
            <div id="scan-hint" class="text-center py-6">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-400 text-sm">Hasil scan akan muncul di sini</p>
            </div>

            {{-- Scan result --}}
            <div id="scan-result" class="hidden">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-800">QR Code berhasil discan!</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Link yang ditemukan</p>
                    <p id="result-url" class="text-sm text-[#0D8B41] font-medium break-all"></p>
                </div>

                <div class="flex gap-2">
                    <a href="#" id="result-open-btn" target="_blank"
                        class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#0D8B41] hover:bg-[#085c2b] text-white font-bold text-sm rounded-xl transition shadow-md active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka di Tab Baru
                    </a>
                    <button onclick="resetScanner()"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Scan Lagi
                    </button>
                </div>

                {{-- Scan timestamp --}}
                <p id="result-time" class="text-xs text-gray-400 text-center mt-3"></p>
            </div>

            {{-- Error state --}}
            <div id="scan-error" class="hidden text-center py-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">Kamera tidak dapat diakses</p>
                <p id="scan-error-msg" class="text-xs text-gray-400 mb-4">Pastikan browser mendapat izin kamera.</p>
                <button onclick="startScanner()"
                    class="px-4 py-2 text-sm font-semibold text-[#0D8B41] border border-green-200 rounded-xl hover:bg-green-50 transition">
                    Coba Lagi
                </button>
            </div>

        </div>
    </div>

    {{-- Tips --}}
    <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-4">
        <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Tips Scan QR Code
        </h4>
        <ul class="text-xs text-blue-700 space-y-1">
            <li class="flex items-start gap-1.5"><span class="text-blue-400 mt-0.5">•</span> Pastikan QR Code terlihat jelas dan tidak buram</li>
            <li class="flex items-start gap-1.5"><span class="text-blue-400 mt-0.5">•</span> Jauhkan/dekatkan kamera hingga QR Code masuk ke dalam bingkai</li>
            <li class="flex items-start gap-1.5"><span class="text-blue-400 mt-0.5">•</span> Pastikan pencahayaan cukup dan tidak ada bayangan</li>
            <li class="flex items-start gap-1.5"><span class="text-blue-400 mt-0.5">•</span> Browser akan meminta izin akses kamera — klik Izinkan</li>
        </ul>
    </div>

</div>

@endsection

@push('scripts')
{{-- html5-qrcode library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    /* Scan line animation */
    #scan-line {
        animation: scanMove 2s ease-in-out infinite;
    }
    @keyframes scanMove {
        0%   { top: 4px;   opacity: 1; }
        50%  { top: calc(100% - 4px); opacity: 1; }
        100% { top: 4px;   opacity: 1; }
    }

    /* Hide html5-qrcode default UI elements */
    #qr-reader__scan_region { border: none !important; }
    #qr-reader__camera_selection, #qr-reader__filescan_input { display: none !important; }
    #qr-reader__status_span { display: none !important; }
    #qr-reader { border: none !important; }
    #qr-reader video { border-radius: 0 !important; width: 100% !important; height: 100% !important; object-fit: cover !important; }
</style>

<script>
    let html5QrCode = null;
    let scanDone    = false;

    // ── Start Scanner ─────────────────────────────────────────────────────
    function startScanner() {
        const initScreen = document.getElementById('cam-init');
        initScreen.innerHTML = `
            <div class="flex flex-col items-center gap-3 text-white">
                <svg class="w-10 h-10 animate-spin text-white/60" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm">Mengaktifkan kamera...</p>
            </div>
        `;

        html5QrCode = new Html5Qrcode("qr-reader");

        Html5Qrcode.getCameras().then(function (devices) {
            if (devices && devices.length) {
                // Prefer back camera
                const camId = devices.find(d => /back|rear|environment/i.test(d.label))?.id || devices[devices.length - 1].id;

                html5QrCode.start(
                    camId,
                    { fps: 15, qrbox: { width: 200, height: 200 }, aspectRatio: 1.0 },
                    onScanSuccess,
                    onScanFailure
                ).then(function () {
                    // Camera started
                    initScreen.classList.add('hidden');
                    updateStatus('scanning', 'Kamera aktif – arahkan ke QR Code');
                    document.getElementById('scanner-controls').style.removeProperty('display');
                }).catch(function (err) {
                    showError('Tidak dapat memulai kamera: ' + err);
                });
            } else {
                showError('Tidak ditemukan kamera pada perangkat ini.');
            }
        }).catch(function (err) {
            showError('Akses kamera ditolak. Izinkan akses kamera di browser Anda.');
        });
    }

    // ── On Scan Success ───────────────────────────────────────────────────
    function onScanSuccess(decodedText) {
        if (scanDone) return;
        scanDone = true;

        // Stop scanner
        if (html5QrCode) {
            html5QrCode.stop().catch(() => {});
        }

        // Show success overlay briefly
        const successOverlay = document.getElementById('scan-success-overlay');
        successOverlay.classList.remove('hidden');

        updateStatus('success', 'QR Code berhasil discan!');
        document.getElementById('scanner-controls').style.display = 'none';

        // Delay lalu buka URL + tampilkan result
        setTimeout(function () {
            successOverlay.classList.add('hidden');

            // Tampilkan result panel
            document.getElementById('scan-hint').classList.add('hidden');
            document.getElementById('scan-result').classList.remove('hidden');
            document.getElementById('result-url').textContent = decodedText;
            document.getElementById('result-time').textContent = 'Discan pada ' + new Date().toLocaleString('id-ID');

            // Set tombol buka
            const openBtn = document.getElementById('result-open-btn');
            openBtn.href = decodedText;

            // Auto-buka di tab baru
            try {
                const parsed = new URL(decodedText);
                if (['http:', 'https:'].includes(parsed.protocol)) {
                    window.open(decodedText, '_blank', 'noopener,noreferrer');
                }
            } catch (e) {
                // Bukan URL valid, tetap tampilkan hasil
            }
        }, 1200);
    }

    // ── On Scan Failure (frame tidak berisi QR, diabaikan) ───────────────
    function onScanFailure(error) {
        // Diabaikan – scanning terus berjalan
    }

    // ── Stop Scanner ──────────────────────────────────────────────────────
    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(function () {
                document.getElementById('scanner-controls').style.display = 'none';
                updateStatus('idle', 'Kamera dihentikan');

                // Tampilkan tombol mulai ulang
                const init = document.getElementById('cam-init');
                init.classList.remove('hidden');
                init.innerHTML = `
                    <div class="flex flex-col items-center gap-3 text-white text-center">
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium">Kamera dihentikan</p>
                        <button onclick="startScanner()" class="px-6 py-2.5 bg-[#0D8B41] text-white font-bold rounded-xl text-sm">Mulai Lagi</button>
                    </div>
                `;
            }).catch(() => {});
        }
    }

    // ── Reset untuk scan berikutnya ────────────────────────────────────────
    function resetScanner() {
        scanDone = false;
        document.getElementById('scan-result').classList.add('hidden');
        document.getElementById('scan-hint').classList.remove('hidden');
        document.getElementById('result-url').textContent = '';

        // Restart camera
        const init = document.getElementById('cam-init');
        init.classList.remove('hidden');
        init.innerHTML = `
            <div class="flex flex-col items-center gap-3 text-white text-center px-6">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium">Siap scan berikutnya</p>
                <button onclick="startScanner()" class="px-6 py-2.5 bg-[#0D8B41] text-white font-bold rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3l14 9-14 9V3z"/>
                    </svg>
                    Mulai Scan
                </button>
            </div>
        `;
        updateStatus('idle', 'Kamera belum aktif');
    }

    // ── Show Error ────────────────────────────────────────────────────────
    function showError(msg) {
        document.getElementById('cam-init').classList.add('hidden');
        document.getElementById('scan-hint').classList.add('hidden');
        document.getElementById('scan-error').classList.remove('hidden');
        document.getElementById('scan-error-msg').textContent = msg;
        updateStatus('error', 'Error kamera');
    }

    // ── Update Status Bar ─────────────────────────────────────────────────
    function updateStatus(state, text) {
        const dot  = document.getElementById('status-dot');
        const span = document.getElementById('status-text');
        span.textContent = text;
        dot.className = 'w-2 h-2 rounded-full ' + {
            idle    : 'bg-gray-300',
            scanning: 'bg-green-400 animate-pulse',
            success : 'bg-green-500',
            error   : 'bg-red-400',
        }[state];
    }
</script>
@endpush
