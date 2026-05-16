@extends('layouts.admin')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
    <div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1" style="color:#60a5fa; letter-spacing:0.15em;">MANAJEMEN</p>
        <h1 class="text-2xl font-black tracking-tight" style="color:#1e3a5f; letter-spacing:-0.5px;">Kelola Menu</h1>
        <p class="text-sm mt-0.5" style="color:rgba(30,58,95,0.5);">Tambah, edit, dan hapus menu makanan & minuman</p>
    </div>
    <a href="{{ route('menu.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0"
       style="background:#1e3a5f; color:#fff;"
       onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
       onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
        <i class="bi bi-plus-lg"></i> Tambah Menu
    </a>
</div>

@php
    $totalMenu     = $menu->count();
    $totalTersedia = $menu->where('status', 'tersedia')->count();
    $totalHabis    = $totalMenu - $totalTersedia;
@endphp

<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:12px;">
    <div class="rounded-2xl p-5" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.4); letter-spacing:0.12em;">TOTAL MENU</div>
        <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalMenu }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.4);">menu terdaftar</div>
    </div>
    <div class="rounded-2xl p-5" style="background:#60a5fa;">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(30,58,95,0.6); letter-spacing:0.12em;">TERSEDIA</div>
        <div class="text-4xl font-black" style="color:#1e3a5f; letter-spacing:-1px;">{{ $totalTersedia }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(30,58,95,0.6);">siap dipesan</div>
    </div>
    <div class="rounded-2xl p-5" style="background:#1e3a5f;">
        <div class="text-xs font-bold tracking-widest mb-2" style="color:rgba(255,255,255,0.4); letter-spacing:0.12em;">HABIS</div>
        <div class="text-4xl font-black" style="color:#fff; letter-spacing:-1px;">{{ $totalHabis }}</div>
        <div class="text-xs mt-1 font-semibold" style="color:rgba(255,255,255,0.4);">stok habis</div>
    </div>
</div>

<div class="rounded-2xl overflow-hidden" style="background:#fff; border:1px solid rgba(30,58,95,0.08);">
    <div class="px-6 py-4" style="border-bottom:1px solid rgba(30,58,95,0.06);">
        <h3 class="text-sm font-bold" style="color:#1e3a5f;">Daftar Menu</h3>
        <p class="text-xs mt-0.5" style="color:rgba(30,58,95,0.4);">{{ $totalMenu }} menu terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:rgba(30,58,95,0.02); border-bottom:1px solid rgba(30,58,95,0.06);">
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase w-12" style="color:rgba(30,58,95,0.35);">No</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase w-16" style="color:rgba(30,58,95,0.35);">Foto</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Nama Menu</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Harga</th>
                    <th class="text-left px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold tracking-widest uppercase" style="color:rgba(30,58,95,0.35);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menu as $m)
                <tr style="border-bottom:1px solid rgba(30,58,95,0.04);"
                    onmouseover="this.style.background='rgba(30,58,95,0.02)'"
                    onmouseout="this.style.background='transparent'">
                    <td class="px-5 py-3.5 text-xs" style="color:rgba(30,58,95,0.35);">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3.5">
                        @if($m->foto)
                            <img src="{{ asset('storage/'.$m->foto) }}" alt="{{ $m->nama_menu }}"
                                 class="w-11 h-11 rounded-xl object-cover"
                                 style="border:2px solid rgba(30,58,95,0.08);">
                        @else
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                 style="background:#eef2ff;">
                                <i class="bi bi-image text-sm" style="color:rgba(30,58,95,0.3);"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 font-bold capitalize" style="color:#1e3a5f;">{{ $m->nama_menu }}</td>
                    <td class="px-5 py-3.5 text-xs font-semibold capitalize" style="color:rgba(30,58,95,0.5);">{{ $m->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-5 py-3.5 font-black" style="color:#1e3a5f;">Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5">
                        @if($m->status == 'tersedia')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(34,197,94,0.1); color:#15803d;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#22c55e;"></span> Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:rgba(239,68,68,0.08); color:#ef4444;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#ef4444;"></span> Habis
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('menu.edit', $m->id_menu) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                               style="background:#eef2ff; color:#1e3a5f; border:none;"
                               onmouseover="this.style.background='#1e3a5f';this.style.color='#fff'"
                               onmouseout="this.style.background='#eef2ff';this.style.color='#1e3a5f'">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('menu.destroy', $m->id_menu) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this, 'menu ini')"
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
                    <td colspan="7" class="px-5 py-12 text-center text-sm" style="color:rgba(30,58,95,0.3);">
                        <i class="bi bi-journal-text text-3xl block mb-2"></i>
                        Belum ada menu. Klik "Tambah Menu" untuk mulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div id="deleteConfirmModal" class="delete-modal-overlay">
    <div class="delete-modal-box">
        <div class="delete-modal-icon">
            <i class="bi bi-trash3-fill"></i>
        </div>

        <h3 class="delete-modal-title">Hapus Data?</h3>

        <p class="delete-modal-text" id="deleteModalText">
            Yakin ingin menghapus menu ini?
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