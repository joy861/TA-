@extends('layouts.admin')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
    <div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1" style="color:#60a5fa; letter-spacing:0.15em;">MANAJEMEN</p>
        <h1 class="text-2xl font-black tracking-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">Kelola Meja</h1>
        <p class="text-sm mt-0.5" style="color:rgba(30,58,95,0.5);">Tambah, edit, dan hapus meja restoran</p>
    </div>
    <button onclick="openModal('modalTambah')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0"
            style="background:#1e3a5f; color:#fff;"
            onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
            onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
        <i class="bi bi-plus-lg"></i> Tambah Meja
    </button>
</div>

@php
    $totalMeja   = $meja->count();
    $totalKosong = $meja->where('status', 'kosong')->count();
    $totalTerisi = $totalMeja - $totalKosong;
@endphp

<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:12px;">
    <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TERSEDIA</div>
        <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalKosong }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.4);">meja siap dipakai</div>
    </div>
    <div class="rounded-2xl p-5" style="background:#60a5fa;">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.6); letter-spacing:0.12em;">TERISI</div>
        <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalTerisi }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.6);">sedang digunakan</div>
    </div>
    <div class="rounded-2xl p-5" style="background:#1e3a5f;">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(255,255,255,0.4); letter-spacing:0.12em;">TOTAL</div>
        <div class="text-4xl font-black" style="color:#fff; letter-spacing:-1px;">{{ $totalMeja }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(255,255,255,0.4);">meja terdaftar</div>
    </div>
</div>

<div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <div class="px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <h3 class="text-sm font-bold" style="color:#1e3a5f;">Daftar Meja</h3>
        <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">{{ $totalMeja }} meja terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase w-14" style="color:rgba(30,58,95,0.35);">No</th>
                    <th class="text-left px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Nomor Meja</th>
                    <th class="text-center px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Status</th>
                    <th class="text-right px-6 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meja as $m)
                <tr style="border-bottom:1px solid rgba(30,58,95,0.04);"
                    onmouseover="this.style.background='rgba(30,58,95,0.02)'"
                    onmouseout="this.style.background='transparent'">
                    <td class="px-6 py-4 text-xs" style="color:rgba(30,58,95,0.35);">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:#1e3a5f;">
                                <i class="bi bi-grid-1x2-fill text-sm" style="color:#60a5fa;"></i>
                            </div>
                            <div>
                                <div class="font-black" style="color:#1e3a5f;">Meja {{ $m->nomor_meja }}</div>
                                @if(isset($m->kapasitas))
                                    <div class="text-xs mt-0.5 font-semibold" style="color:rgba(30,58,95,0.4);">Kapasitas {{ $m->kapasitas }} orang</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($m->status == 'kosong')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(34,197,94,0.1); color:#15803d;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#22c55e;"></span> Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(239,68,68,0.08); color:#ef4444;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#ef4444;"></span> Terisi
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal({{ $m->id_meja }}, {{ $m->nomor_meja }}, {{ $m->kapasitas ?? 4 }}, '{{ $m->status }}')"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                                    style="background:#eef2ff; color:#1e3a5f; border:none;"
                                    onmouseover="this.style.background='#1e3a5f';this.style.color='#fff'"
                                    onmouseout="this.style.background='#eef2ff';this.style.color='#1e3a5f'">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form action="{{ route('meja.destroy', $m->id_meja) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this, 'meja ini')"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                                        style="background:rgba(239,68,68,0.08); color:#ef4444; border:none;"
                                        onmouseover="this.style.background='#ef4444';this.style.color='#fff'"
                                        onmouseout="this.style.background='rgba(239,68,68,0.08)';this.style.color='#ef4444'">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                        <i class="bi bi-grid-3x3-gap text-3xl block mb-2"></i>Belum ada meja terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(30,58,95,0.5); backdrop-filter:blur(4px);">
    <div id="modalTambahBox" class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(30,58,95,0.06); background:#1e3a5f;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#60a5fa;">
                    <i class="bi bi-plus-square-fill text-sm" style="color:#1e3a5f;"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black" style="color:#fff;">Tambah Meja</h3>
                    <p class="text-xs" style="color:rgba(255,255,255,0.5);">Daftarkan meja baru ke sistem</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambah')" style="background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.5);">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <form action="{{ route('meja.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Nomor Meja</label>
                        <div class="flex rounded-xl overflow-hidden" style="border:1.5px solid rgba(30,58,95,0.12);"
                             onfocusin="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                             onfocusout="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <button type="button" onclick="stepValue('tambah_nomor',-1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-right:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="tambah_nomor" name="nomor_meja" min="1" value="1"
                                   class="flex-1 text-center text-sm font-black outline-none py-2.5"
                                   style="border:none; color:#1e3a5f; background:transparent; -moz-appearance:textfield;" required>
                            <button type="button" onclick="stepValue('tambah_nomor',1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-left:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Kapasitas <span class="text-xs font-normal" style="color:rgba(30,58,95,0.4);">(orang)</span></label>
                        <div class="flex rounded-xl overflow-hidden" style="border:1.5px solid rgba(30,58,95,0.12);"
                             onfocusin="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                             onfocusout="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <button type="button" onclick="stepValue('tambah_kapasitas',-1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-right:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="tambah_kapasitas" name="kapasitas" min="1" value="4"
                                   class="flex-1 text-center text-sm font-black outline-none py-2.5"
                                   style="border:none; color:#1e3a5f; background:transparent; -moz-appearance:textfield;" required>
                            <button type="button" onclick="stepValue('tambah_kapasitas',1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-left:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Status Awal</label>
                    <select name="status" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                            style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                            onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                            onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                        <option value="kosong" selected>Tersedia</option>
                        <option value="terisi">Terisi</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4" style="background:rgba(30,58,95,0.02); border-top:1px solid rgba(30,58,95,0.06);">
                <button type="button" onclick="closeModal('modalTambah')"
                        class="text-sm font-semibold px-5 py-2.5 rounded-xl"
                        style="background:#eef2ff; color:#1e3a5f; border:none;">Batal</button>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;"
                        onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                        onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                    <i class="bi bi-check2"></i> Simpan Meja
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(30,58,95,0.5); backdrop-filter:blur(4px);">
    <div id="modalEditBox" class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(30,58,95,0.06); background:#1e3a5f;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#60a5fa;">
                    <i class="bi bi-grid-1x2-fill text-sm" style="color:#1e3a5f;"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black" style="color:#fff;">Edit Meja</h3>
                    <p id="editModalSubtitle" class="text-xs" style="color:rgba(255,255,255,0.5);">Ubah detail meja</p>
                </div>
            </div>
            <button onclick="closeModal('modalEdit')" style="background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.5);">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
        <form id="formEdit" action="" method="POST">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Nomor Meja</label>
                        <div class="flex rounded-xl overflow-hidden" style="border:1.5px solid rgba(30,58,95,0.12);"
                             onfocusin="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                             onfocusout="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <button type="button" onclick="stepValue('edit_nomor',-1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-right:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="edit_nomor" name="nomor_meja" min="1"
                                   class="flex-1 text-center text-sm font-black outline-none py-2.5"
                                   style="border:none; color:#1e3a5f; background:transparent; -moz-appearance:textfield;" required>
                            <button type="button" onclick="stepValue('edit_nomor',1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-left:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Kapasitas <span class="text-xs font-normal" style="color:rgba(30,58,95,0.4);">(orang)</span></label>
                        <div class="flex rounded-xl overflow-hidden" style="border:1.5px solid rgba(30,58,95,0.12);"
                             onfocusin="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                             onfocusout="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <button type="button" onclick="stepValue('edit_kapasitas',-1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-right:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="edit_kapasitas" name="kapasitas" min="1"
                                   class="flex-1 text-center text-sm font-black outline-none py-2.5"
                                   style="border:none; color:#1e3a5f; background:transparent; -moz-appearance:textfield;" required>
                            <button type="button" onclick="stepValue('edit_kapasitas',1)"
                                    class="w-10 flex items-center justify-center flex-shrink-0"
                                    style="background:#eef2ff; border:none; cursor:pointer; color:#1e3a5f; border-left:1px solid rgba(30,58,95,0.1);"
                                    onmouseover="this.style.background='#60a5fa'" onmouseout="this.style.background='#eef2ff'">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2" style="color:#1e3a5f;">Status Meja</label>
                    <select id="edit_status" name="status" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                            style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                            onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                            onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                        <option value="kosong">Tersedia</option>
                        <option value="terisi">Terisi</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4" style="background:rgba(30,58,95,0.02); border-top:1px solid rgba(30,58,95,0.06);">
                <button type="button" onclick="closeModal('modalEdit')"
                        class="text-sm font-semibold px-5 py-2.5 rounded-xl"
                        style="background:#eef2ff; color:#1e3a5f; border:none;">Batal</button>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;"
                        onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                        onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                    <i class="bi bi-check2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function stepValue(id, delta) {
        const input = document.getElementById(id);
        const min = parseInt(input.min) || 1;
        const next = (parseInt(input.value) || 0) + delta;
        input.value = next < min ? min : next;
    }
    function openModal(id) {
        const o=document.getElementById(id), b=document.getElementById(id+'Box');
        o.classList.remove('hidden'); o.classList.add('flex');
        setTimeout(()=>{ b.classList.remove('scale-95','opacity-0'); b.classList.add('scale-100','opacity-100'); },10);
    }
    function closeModal(id) {
        const o=document.getElementById(id), b=document.getElementById(id+'Box');
        b.classList.remove('scale-100','opacity-100'); b.classList.add('scale-95','opacity-0');
        setTimeout(()=>{ o.classList.add('hidden'); o.classList.remove('flex'); },200);
    }
    function openEditModal(id, nomor, kapasitas, status) {
        document.getElementById('formEdit').action=`/admin/meja/${id}`;
        document.getElementById('edit_nomor').value=nomor;
        document.getElementById('edit_kapasitas').value=kapasitas;
        document.getElementById('edit_status').value=status;
        document.getElementById('editModalSubtitle').textContent=`Mengubah Meja ${nomor}`;
        openModal('modalEdit');
    }
    ['modalTambah','modalEdit'].forEach(id=>{
        document.getElementById(id).addEventListener('click',function(e){ if(e.target===this) closeModal(id); });
    });
    document.addEventListener('keydown',e=>{ if(e.key==='Escape'){closeModal('modalTambah');closeModal('modalEdit');} });
</script>

<div id="deleteConfirmModal" class="delete-modal-overlay">
    <div class="delete-modal-box">
        <div class="delete-modal-icon">
            <i class="bi bi-trash3-fill"></i>
        </div>

        <h3 class="delete-modal-title">Hapus Data?</h3>

        <p class="delete-modal-text" id="deleteModalText">
            Yakin ingin menghapus meja ini?
        </p>

        <p class="delete-modal-subtext">
            Data yang sudah dihapus tidak bisa dikembalikan.
        </p>

        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-btn delete-modal-cancel" onclick="closeDeleteModal()">
                Batal
            </button>

            <button type="button" class="delete-modal-btn delete-modal-submit" onclick="submitDeleteForm()">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<style>
    .delete-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(8, 20, 43, 0.62);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .delete-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .delete-modal-box {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 28px;
        padding: 34px 28px 26px;
        text-align: center;
        box-shadow: 0 30px 80px rgba(8, 20, 43, 0.28);
        border: 1px solid rgba(226, 232, 240, 0.9);
        transform: translateY(20px) scale(0.96);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .delete-modal-overlay.show .delete-modal-box {
        transform: translateY(0) scale(1);
    }

    .delete-modal-box::before {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: rgba(239, 68, 68, 0.08);
        top: -90px;
        right: -90px;
    }

    .delete-modal-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #fff1f2;
        border: 4px solid #fecaca;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        position: relative;
        z-index: 2;
    }

    .delete-modal-title {
        font-size: 28px;
        font-weight: 900;
        color: #1e3a5f;
        margin-bottom: 10px;
        letter-spacing: -0.04em;
        position: relative;
        z-index: 2;
    }

    .delete-modal-text {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .delete-modal-subtext {
        font-size: 14px;
        color: #7188a7;
        line-height: 1.6;
        margin-bottom: 26px;
        position: relative;
        z-index: 2;
    }

    .delete-modal-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        position: relative;
        z-index: 2;
    }

    .delete-modal-btn {
        border: none;
        border-radius: 16px;
        padding: 13px 20px;
        min-width: 120px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .delete-modal-cancel {
        background: #eef2f7;
        color: #475569;
    }

    .delete-modal-cancel:hover {
        background: #e2e8f0;
    }

    .delete-modal-submit {
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #ffffff;
        box-shadow: 0 14px 30px rgba(239, 68, 68, 0.24);
    }

    .delete-modal-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 35px rgba(239, 68, 68, 0.30);
    }

    @media (max-width: 576px) {
        .delete-modal-box {
            padding: 28px 20px 22px;
            border-radius: 22px;
        }

        .delete-modal-title {
            font-size: 24px;
        }

        .delete-modal-actions {
            flex-direction: column-reverse;
        }

        .delete-modal-btn {
            width: 100%;
        }
    }
</style>

<script>
    let selectedDeleteForm = null;

    function openDeleteModal(button, label = 'data ini') {
        selectedDeleteForm = button.closest('form');

        const modal = document.getElementById('deleteConfirmModal');
        const text = document.getElementById('deleteModalText');

        if (text) {
            text.textContent = 'Yakin ingin menghapus ' + label + '?';
        }

        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');

        if (modal) {
            modal.classList.remove('show');
        }

        document.body.style.overflow = '';
        selectedDeleteForm = null;
    }

    function submitDeleteForm() {
        if (selectedDeleteForm) {
            selectedDeleteForm.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('deleteConfirmModal');

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === this) {
                    closeDeleteModal();
                }
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

@endsection