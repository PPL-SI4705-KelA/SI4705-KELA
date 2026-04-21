<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Kegiatan Penghijauan - {{ config('app.name', 'Greenovate') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Using CDN for quick preview if Vite isn't built -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
              tailwind.config = {
                theme: {
                  extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#f0fdf4', 100: '#dcfce7', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 900: '#14532d' }
                    }
                  }
                }
              }
            </script>
        @endif
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 24px -10px rgba(22, 163, 74, 0.15);
                border-color: #dcfce7;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased selection:bg-brand-500 selection:text-white pb-12">
        
        <!-- Navigation Bar -->
        <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex justify-start items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <div class="h-8 w-8 bg-brand-500 text-white rounded-lg flex items-center justify-center font-bold text-xl">G</div>
                            <span class="font-bold text-xl text-gray-900 tracking-tight">Greenovate</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Kegiatan Penghijauan</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl">Mari bergabung dalam berbagai kegiatan penanaman pohon untuk masa depan bumi yang lebih hijau.</p>
                
                <!-- Filter Form -->
                <form action="{{ route('kegiatan.index') }}" method="GET" class="mt-8 bg-gray-50 p-4 rounded-xl border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full relative">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="lokasi" id="lokasi" value="{{ request('lokasi') }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm py-2 border px-3" placeholder="Contoh: Bandung">
                        </div>
                    </div>
                    
                    <div class="w-full">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm py-2 border px-3 text-gray-600">
                    </div>
                    
                    <div class="w-full">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm py-2 border px-3 bg-white">
                            <option value="">Semua Status</option>
                            <option value="Belum Mulai" {{ request('status') == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                            <option value="Sedang Berjalan" {{ request('status') == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:w-auto flex gap-2">
                        <button type="submit" class="w-full md:w-auto inline-flex justify-center rounded-md border border-transparent bg-brand-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors">
                            Cari
                        </button>
                        <a href="{{ route('kegiatan.index') }}" class="w-full md:w-auto inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content Section -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            
            @if($kegiatans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kegiatans as $kegiatan)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden card-hover flex flex-col h-full group relative">
                        <!-- Card Header image / badge -->
                        <div class="h-4 bg-brand-500/10"></div>
                        
                        <div class="p-6 flex-grow">
                            <!-- Status Badge -->
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-4
                                {{ $kegiatan->status === 'Belum Mulai' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $kegiatan->status === 'Sedang Berjalan' ? 'bg-brand-100 text-brand-800' : '' }}
                                {{ $kegiatan->status === 'Selesai' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                @if($kegiatan->status === 'Belum Mulai')
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                @elseif($kegiatan->status === 'Sedang Berjalan')
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-brand-400 animate-pulse" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                @else
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                @endif
                                {{ $kegiatan->status }}
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 group-hover:text-brand-600 transition-colors">
                                {{ $kegiatan->nama_kegiatan }}
                            </h3>
                            
                            <div class="mt-4 space-y-3">
                                <div class="flex items-start text-sm text-gray-600">
                                    <svg class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="leading-5">{{ $kegiatan->lokasi }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    @php
                                        // simple date format since Carbon isn't explicitly configured in Blade here
                                        $parsedDate = date('d F Y', strtotime($kegiatan->tanggal));
                                    @endphp
                                    <span>{{ $parsedDate }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 pt-0 mt-auto">
                            <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 uppercase font-semibold">Tercapai</span>
                                    <span class="font-medium text-gray-900">{{ number_format($kegiatan->target_pohon) }} <span class="text-gray-500 text-sm font-normal">pohon</span></span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-xs text-gray-500 uppercase font-semibold">Kuota Sisa</span>
                                    <span class="font-bold {{ $kegiatan->kuota_tersisa > 0 ? 'text-brand-600' : 'text-red-600' }}">
                                        {{ $kegiatan->kuota_tersisa > 0 ? $kegiatan->kuota_tersisa . ' Orang' : 'Penuh' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-5">
                                <a href="#" class="block w-full text-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-brand-700 bg-brand-50 hover:bg-brand-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors {{ $kegiatan->kuota_tersisa == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Detail Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-10">
                    {{ $kegiatans->withQueryString()->links('pagination::tailwind') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-200 border-dashed">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kegiatan</h3>
                    <p class="mt-1 text-sm text-gray-500">Silakan ubah filter pencarian atau kembali lagi nanti untuk kegiatan baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hapus Semua Filter
                        </a>
                    </div>
                </div>
            @endif
        </main>
        
        <!-- Footer simple -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Greenovate. Hak Cipta Dilindungi.</p>
            </div>
        </footer>
    </body>
</html>
