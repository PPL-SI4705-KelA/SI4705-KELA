@extends('layouts.admin')

@section('title', 'Tambah Jenis Pohon - Greennovate Admin')
@section('page-title', 'Tambah Jenis Pohon')
@section('page-subtitle', 'Tambahkan data jenis pohon dan harga baru')

@section('content')
<div class="w-full max-w-2xl">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.jenis-pohon.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 mb-2 inline-block">
            ← Kembali ke Daftar Jenis Pohon
        </a>
        <h2 class="text-xl font-bold text-gray-900">Tambah Jenis Pohon</h2>
        <p class="text-sm text-gray-500 mt-1">Isi form di bawah untuk menambahkan jenis pohon baru.</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.jenis-pohon.store') }}"
              id="form-create-pohon"
              class="flex flex-col gap-5">
            @csrf

            @include('admin.jenis-pohon._form')

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.jenis-pohon.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        id="btn-simpan"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
