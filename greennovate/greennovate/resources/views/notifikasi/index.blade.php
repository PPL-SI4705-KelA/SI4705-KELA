@extends('layouts.auth')

@section('title', 'Notifikasi – Greennovate')

@section('content')
<div class="w-full max-w-3xl px-4 mt-8 pb-16">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola notifikasi dan pesan penting Anda</p>
        </div>

        @if($belumDibaca->count() > 0)
            <form method="POST" action="{{ route('notifikasi.baca-semua') }}" id="form-baca-semua">
                @csrf
                @method('PATCH')
                <button type="submit" id="btn-baca-semua"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-full border border-green-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai semua sudah dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div id="flash-success"
             class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
        <a href="{{ route('notifikasi.index', ['tab' => 'belum_dibaca']) }}"
           id="tab-belum"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition {{ $tab === 'belum_dibaca' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Belum Dibaca
            @if($belumDibaca->count() > 0)
                <span class="ml-1.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                    {{ $belumDibaca->count() }}
                </span>
            @endif
        </a>
        <a href="{{ route('notifikasi.index', ['tab' => 'sudah_dibaca']) }}"
           id="tab-sudah"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition {{ $tab === 'sudah_dibaca' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Sudah Dibaca
        </a>
    </div>

    {{-- Konten Tab: Belum Dibaca --}}
    @if($tab === 'belum_dibaca')
        @if($belumDibaca->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-700" id="empty-state-text">Tidak ada notifikasi baru</p>
                <p class="text-sm text-gray-400 mt-1">Semua notifikasi Anda sudah terbaca.</p>
            </div>
        @else
            <div class="space-y-3" id="notif-list-belum">
                @foreach($belumDibaca as $notif)
                    <div class="notif-card group bg-white border-l-4 border-[#0D8B41] rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-4 flex items-start gap-4"
                         id="notif-{{ $notif->id }}"
                         data-id="{{ $notif->id }}">

                        {{-- Ikon Tipe --}}
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-xl flex-shrink-0">
                            {{ $notif->ikonTipe() }}
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $notif->warnaTipe() }}">
                                    {{ ucfirst($notif->tipe) }}
                                </span>
                                <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0" title="Belum dibaca"></span>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm leading-snug">{{ $notif->judul }}</p>
                            <p class="text-gray-500 text-sm mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>

                        {{-- Tombol Tandai Dibaca --}}
                        <div class="flex-shrink-0">
                            <form method="POST"
                                  action="{{ route('notifikasi.baca', $notif->id) }}"
                                  class="form-tandai-baca"
                                  data-id="{{ $notif->id }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn-tandai-baca flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tandai sudah dibaca
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    {{-- Konten Tab: Sudah Dibaca --}}
    @else
        @if($sudahDibaca->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-600">Belum ada notifikasi yang dibaca</p>
                <p class="text-sm text-gray-400 mt-1">Notifikasi yang sudah Anda tandai akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($sudahDibaca as $notif)
                    <div class="bg-white border border-gray-100 rounded-xl p-4 flex items-start gap-4 opacity-75">

                        {{-- Ikon Tipe --}}
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-xl flex-shrink-0">
                            {{ $notif->ikonTipe() }}
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $notif->warnaTipe() }}">
                                    {{ ucfirst($notif->tipe) }}
                                </span>
                                <span class="text-xs text-gray-400">✓ Dibaca</span>
                            </div>
                            <p class="font-semibold text-gray-700 text-sm leading-snug">{{ $notif->judul }}</p>
                            <p class="text-gray-400 text-sm mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                            <div class="flex gap-3 mt-2">
                                <p class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                                @if($notif->read_at)
                                    <p class="text-xs text-gray-300">· Dibaca {{ $notif->read_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        || '{{ csrf_token() }}';

    // ── Tandai Satu Dibaca (AJAX) ──────────────────────────────────────
    document.querySelectorAll('.form-tandai-baca').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const notifId = this.dataset.id;
            const card    = document.getElementById('notif-' + notifId);
            const btn     = this.querySelector('.btn-tandai-baca');

            // Disable button sementara
            btn.disabled    = true;
            btn.textContent = 'Memproses...';

            fetch('{{ url("/notifikasi") }}/' + notifId + '/baca', {
                method : 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept'      : 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                // Animasi hilang
                card.style.transition = 'all 0.4s ease';
                card.style.opacity    = '0';
                card.style.transform  = 'translateX(40px)';
                setTimeout(function () { card.remove(); }, 400);

                // Update badge navbar
                updateBadges(data.unread_count);

                // Cek apakah list kosong
                setTimeout(checkEmpty, 420);
            })
            .catch(function () {
                btn.disabled    = false;
                btn.textContent = 'Tandai sudah dibaca';
            });
        });
    });

    // ── Tandai Semua Dibaca (AJAX) ─────────────────────────────────────
    const formBacaSemua = document.getElementById('form-baca-semua');
    if (formBacaSemua) {
        formBacaSemua.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('btn-baca-semua');
            btn.disabled    = true;
            btn.textContent = 'Memproses...';

            fetch('{{ route("notifikasi.baca-semua") }}', {
                method : 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept'      : 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                // Animasi semua kartu hilang
                const cards = document.querySelectorAll('.notif-card');
                cards.forEach(function (card, i) {
                    setTimeout(function () {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity    = '0';
                        card.style.transform  = 'translateX(40px)';
                        setTimeout(function () { card.remove(); }, 300);
                    }, i * 60);
                });

                // Update badges
                updateBadges(0);

                // Sembunyikan tombol "Tandai semua"
                formBacaSemua.style.display = 'none';

                setTimeout(function () {
                    showFlash('Semua notifikasi telah ditandai sebagai sudah dibaca.');
                    checkEmpty();
                }, cards.length * 60 + 350);
            })
            .catch(function () {
                btn.disabled    = false;
                btn.textContent = 'Tandai semua sudah dibaca';
            });
        });
    }

    // ── Helper: update badge counter di navbar ─────────────────────────
    function updateBadges(count) {
        const badges = document.querySelectorAll('.notif-badge-count');
        badges.forEach(function (badge) {
            if (count <= 0) {
                badge.style.display = 'none';
            } else {
                badge.textContent   = count;
                badge.style.display = '';
            }
        });

        // Update tab counter
        const tabCounter = document.querySelector('#tab-belum .notif-tab-count');
        if (tabCounter) {
            if (count <= 0) {
                tabCounter.remove();
            } else {
                tabCounter.textContent = count;
            }
        }
    }

    // ── Helper: tampilkan empty state ──────────────────────────────────
    function checkEmpty() {
        const list = document.getElementById('notif-list-belum');
        if (! list) return;

        if (list.querySelectorAll('.notif-card').length === 0) {
            list.innerHTML = `
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-700">Tidak ada notifikasi baru</p>
                    <p class="text-sm text-gray-400 mt-1">Semua notifikasi Anda sudah terbaca.</p>
                </div>`;
        }
    }

    // ── Helper: tampilkan flash message dinamis ────────────────────────
    function showFlash(msg) {
        const div = document.createElement('div');
        div.className = 'mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium';
        div.innerHTML = `
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ${msg}`;
        const target = document.querySelector('.max-w-3xl');
        const tabs   = document.querySelector('.flex.gap-1.mb-6');
        target.insertBefore(div, tabs);
        setTimeout(function () { div.remove(); }, 5000);
    }

    // ── Auto-dismiss flash ─────────────────────────────────────────────
    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity    = '0';
            setTimeout(function () { flash.remove(); }, 500);
        }, 4000);
    }
});
</script>
@endpush
