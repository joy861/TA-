@extends('layouts.admin')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('menu.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold mb-5 transition-colors"
       style="color:rgba(30,58,95,0.5);"
       onmouseover="this.style.color='#60a5fa'"
       onmouseout="this.style.color='rgba(30,58,95,0.5)'">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Menu
    </a>

    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid rgba(30,58,95,0.08);">

            <div class="px-7 py-5" style="border-bottom:1px solid rgba(30,58,95,0.06); background:#1e3a5f;">
                <h2 class="text-xl font-black" style="color:#fff; letter-spacing:-0.3px;">Tambah Menu Baru</h2>
                <p class="text-sm mt-1" style="color:rgba(255,255,255,0.5);">Lengkapi detail menu untuk ditampilkan di sistem pemesanan</p>
            </div>

            <div class="px-7 py-6">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">DETAIL MENU</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Nama Menu</label>
                        <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" placeholder="Contoh: Nasi Goreng Spesial"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('nama_menu')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Kategori</label>
                            <select name="id_kategori" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                    style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                    onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                    onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}" {{ old('id_kategori')==$k->id_kategori?'selected':'' }}>
                                        {{ ucfirst($k->nama_kategori) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Status</label>
                            <select name="status" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                    style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                    onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                    onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                                <option value="tersedia" {{ old('status','tersedia')=='tersedia'?'selected':'' }}>Tersedia</option>
                                <option value="habis" {{ old('status')=='habis'?'selected':'' }}>Habis</option>
                            </select>
                            @error('status')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Harga</label>
                        <div class="flex rounded-xl overflow-hidden transition-all"
                             style="border:1.5px solid rgba(30,58,95,0.12);"
                             onfocusin="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                             onfocusout="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <span class="px-4 py-2.5 text-sm font-bold flex items-center flex-shrink-0"
                                  style="background:#eef2ff; color:#1e3a5f; border-right:1.5px solid rgba(30,58,95,0.1);">Rp</span>
                            <input type="number" name="harga" value="{{ old('harga') }}" placeholder="0" min="0"
                                   class="flex-1 px-4 py-2.5 text-sm outline-none font-bold"
                                   style="color:#1e3a5f; border:none; background:transparent;" required>
                        </div>
                        <p class="text-xs mt-1.5" style="color:rgba(30,58,95,0.4);">Tulis harga tanpa titik atau koma (contoh: 18000).</p>
                        @error('harga')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="px-7 py-6" style="border-top:1px solid rgba(30,58,95,0.04);">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">FOTO MENU</p>
                <label for="foto" class="block cursor-pointer rounded-xl p-6 text-center transition-all"
                       style="border:2px dashed rgba(30,58,95,0.15); background:#eef2ff;"
                       onmouseover="this.style.borderColor='#60a5fa';this.style.background='rgba(96,165,250,0.08)'"
                       onmouseout="this.style.borderColor='rgba(30,58,95,0.15)';this.style.background='#eef2ff'">
                    <i class="bi bi-cloud-arrow-up text-3xl mb-2 block" style="color:rgba(30,58,95,0.3);"></i>
                    <p class="text-sm font-semibold" style="color:rgba(30,58,95,0.6);">
                        <strong style="color:#1e3a5f;">Klik untuk pilih foto</strong> atau drag & drop
                    </p>
                    <p class="text-xs mt-1" style="color:rgba(30,58,95,0.4);">PNG, JPG, JPEG (Maks. 2MB)</p>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(event)">
                </label>
                <div id="fotoPreview" class="hidden mt-3 text-center">
                    <img id="fotoPreviewImg" src="" alt="Preview" class="max-w-[160px] max-h-[160px] rounded-xl mx-auto"
                         style="border:2px solid rgba(30,58,95,0.1);">
                </div>
                @error('foto')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-3 px-7 py-4" style="background:rgba(30,58,95,0.02); border-top:1px solid rgba(30,58,95,0.06);">
                <a href="{{ route('menu.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl"
                   style="background:#eef2ff; color:#1e3a5f; border:none;"
                   onmouseover="this.style.background='rgba(30,58,95,0.1)'"
                   onmouseout="this.style.background='#eef2ff'">Batal</a>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;"
                        onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                        onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                    <i class="bi bi-check2"></i> Simpan Menu
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('fotoPreviewImg').src = e.target.result;
                document.getElementById('fotoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection