{{-- Partial form untuk create & edit jenis pohon --}}

{{-- Nama Pohon --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nama Pohon <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nama"
           value="{{ old('nama', $jenisPohon->nama ?? '') }}"
           placeholder="Contoh: Mahoni"
           id="input-nama-pohon"
           class="w-full border @error('nama') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Nama Latin --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nama Latin
    </label>
    <input type="text" name="nama_latin"
           value="{{ old('nama_latin', $jenisPohon->nama_latin ?? '') }}"
           placeholder="Contoh: Swietenia macrophylla"
           id="input-nama-latin"
           class="w-full border @error('nama_latin') border-red-400 @else border-gray-300 @enderror
                  rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 italic">
    @error('nama_latin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Kategori & Harga --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kategori <span class="text-red-500">*</span>
        </label>
        <select name="kategori_pohon_id"
                id="select-kategori"
                class="w-full border @error('kategori_pohon_id') border-red-400 @else border-gray-300 @enderror
                       rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}"
                    {{ old('kategori_pohon_id', $jenisPohon->kategori_pohon_id ?? '') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_pohon_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Harga per Pohon (Rp) <span class="text-red-500">*</span>
        </label>
        <input type="number" name="harga" min="1000" max="10000000" step="1"
               value="{{ old('harga', isset($jenisPohon) ? (int) $jenisPohon->harga : '') }}"
               placeholder="Contoh: 50000"
               id="input-harga"
               class="w-full border @error('harga') border-red-400 @else border-gray-300 @enderror
                      rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <p class="mt-1 text-xs text-gray-400">Min: Rp 1.000 — Max: Rp 10.000.000</p>
        @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Status --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Status <span class="text-red-500">*</span>
    </label>
    <select name="status"
            id="select-status"
            class="w-full border @error('status') border-red-400 @else border-gray-300 @enderror
                   rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="active"
            {{ old('status', $jenisPohon->status ?? 'active') === 'active' ? 'selected' : '' }}>
            Aktif
        </option>
        <option value="inactive"
            {{ old('status', $jenisPohon->status ?? '') === 'inactive' ? 'selected' : '' }}>
            Tidak Aktif
        </option>
    </select>
    @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

{{-- Hidden: version (untuk optimistic locking pada edit) --}}
@if(isset($jenisPohon))
    <input type="hidden" name="version" value="{{ $jenisPohon->version }}">
@endif
