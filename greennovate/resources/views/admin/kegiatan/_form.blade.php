{{-- Partial form untuk create & edit kegiatan --}}

{{-- Nama --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nama Kegiatan <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nama"
           value="{{ old('nama', $kegiatan->nama ?? '') }}"
           placeholder="Contoh: Penanaman Pohon Zona A"
           class="w-full border @error('nama') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Lokasi Lahan --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Lokasi Lahan <span class="text-red-500">*</span>
    </label>
    <select name="lokasi_lahan_id"
            class="w-full border @error('lokasi_lahan_id') border-red-400 @else border-gray-300 @enderror
                   rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">-- Pilih Lokasi Lahan --</option>
        @foreach($lokasLahans as $lahan)
            <option value="{{ $lahan->id }}"
                {{ old('lokasi_lahan_id', $kegiatan->lokasi_lahan_id ?? '') == $lahan->id ? 'selected' : '' }}>
                {{ $lahan->nama }}
            </option>
        @endforeach
    </select>
    @error('lokasi_lahan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Petugas --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Petugas <span class="text-red-500">*</span>
    </label>
    <select name="petugas_id"
            class="w-full border @error('petugas_id') border-red-400 @else border-gray-300 @enderror
                   rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">-- Pilih Petugas --</option>
        @foreach($petugasList as $petugas)
            <option value="{{ $petugas->id }}"
                {{ old('petugas_id', $kegiatan->petugas_id ?? '') == $petugas->id ? 'selected' : '' }}>
                {{ $petugas->name }}
            </option>
        @endforeach
    </select>
    @error('petugas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Tanggal & Status --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Tanggal <span class="text-red-500">*</span>
        </label>
        <input type="date" name="tanggal"
               value="{{ old('tanggal', isset($kegiatan) ? $kegiatan->tanggal?->format('Y-m-d') : '') }}"
               class="w-full border @error('tanggal') border-red-400 @else border-gray-300 @enderror
                      rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @error('tanggal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Status <span class="text-red-500">*</span>
        </label>
        <select name="status"
                class="w-full border @error('status') border-red-400 @else border-gray-300 @enderror
                       rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            @foreach(['Persiapan', 'Berlangsung', 'Selesai', 'Dibatalkan'] as $s)
                <option value="{{ $s }}"
                    {{ old('status', $kegiatan->status ?? 'Persiapan') === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Target & Realisasi Pohon --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Target Pohon <span class="text-red-500">*</span>
        </label>
        <input type="number" name="target_pohon" min="0"
               value="{{ old('target_pohon', $kegiatan->target_pohon ?? 0) }}"
               class="w-full border @error('target_pohon') border-red-400 @else border-gray-300 @enderror
                      rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @error('target_pohon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Pohon</label>
        <input type="number" name="realisasi_pohon" min="0"
               value="{{ old('realisasi_pohon', $kegiatan->realisasi_pohon ?? 0) }}"
               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
</div>

{{-- Deskripsi --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="4"
              placeholder="Jelaskan detail kegiatan..."
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                     focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('deskripsi', $kegiatan->deskripsi ?? '') }}</textarea>
</div>
