{{--
    Partial: form fields untuk create & edit kegiatan.
    Dipakai di admin/kegiatan/create.blade.php dan edit.blade.php.
    Variabel $kegiatan opsional (null saat create).
--}}

{{-- Nama --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nama Kegiatan <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nama"
           value="{{ old('nama', $kegiatan->nama ?? '') }}"
           placeholder="Contoh: Tanam Pohon Bersama Samarinda"
           class="w-full border @error('nama') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    @error('nama')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

{{-- Lokasi --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Lokasi <span class="text-red-500">*</span>
    </label>
    <input type="text" name="lokasi"
           value="{{ old('lokasi', $kegiatan->lokasi ?? '') }}"
           placeholder="Contoh: Taman Kota Samarinda"
           class="w-full border @error('lokasi') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    @error('lokasi')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

{{-- Tanggal --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Tanggal Kegiatan <span class="text-red-500">*</span>
    </label>
    <input type="date" name="tanggal"
           value="{{ old('tanggal', isset($kegiatan) ? $kegiatan->tanggal->format('Y-m-d') : '') }}"
           class="w-full border @error('tanggal') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    @error('tanggal')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

{{-- Target & Kuota --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Target Pohon <span class="text-red-500">*</span>
        </label>
        <input type="number" name="target" min="0"
               value="{{ old('target', $kegiatan->target ?? 0) }}"
               class="w-full border @error('target') border-red-400 @else border-gray-300 @enderror
                      rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @error('target')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kuota Peserta <span class="text-red-500">*</span>
        </label>
        <input type="number" name="kuota" min="0"
               value="{{ old('kuota', $kegiatan->kuota ?? 0) }}"
               class="w-full border @error('kuota') border-red-400 @else border-gray-300 @enderror
                      rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @error('kuota')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Status --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Status <span class="text-red-500">*</span>
    </label>
    <select name="status"
            class="w-full border @error('status') border-red-400 @else border-gray-300 @enderror
                   rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @foreach(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'selesai' => 'Selesai'] as $val => $label)
            <option value="{{ $val }}"
                {{ old('status', $kegiatan->status ?? 'aktif') === $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

{{-- Deskripsi --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4"
              placeholder="Jelaskan detail kegiatan..."
              class="w-full border @error('deskripsi') border-red-400 @else border-gray-300 @enderror
                     rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('deskripsi', $kegiatan->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
