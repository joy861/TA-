@extends('layouts.admin')

@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('user.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold mb-5 transition-colors"
       style="color:rgba(30,58,95,0.5);"
       onmouseover="this.style.color='#60a5fa'"
       onmouseout="this.style.color='rgba(30,58,95,0.5)'">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>

    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid rgba(30,58,95,0.08);">

            <div class="px-7 py-5" style="border-bottom:1px solid rgba(30,58,95,0.06); background:#1e3a5f;">
                <h2 class="text-xl font-black" style="color:#fff; letter-spacing:-0.3px;">Tambah User Baru</h2>
                <p class="text-sm mt-1" style="color:rgba(255,255,255,0.5);">Buat akun admin atau kasir baru</p>
            </div>

            <div class="px-7 py-6">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">INFORMASI AKUN</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Made Sari"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('nama')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Contoh: sari_kasir"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('username')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                                   class="w-full rounded-xl px-4 py-2.5 pr-10 text-sm outline-none transition-all"
                                   style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                                   onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                   onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                            <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2"
                                    style="color:rgba(30,58,95,0.4); background:none; border:none; cursor:pointer;">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Role</label>
                        <select name="role" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                            <option value="kasir" {{ old('role')=='kasir'?'selected':'' }}>Kasir</option>
                        </select>
                        @error('role')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="px-7 py-6" style="border-top:1px solid rgba(30,58,95,0.04);">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">KEAMANAN AKUN</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Pertanyaan Keamanan</label>
                        <select name="pertanyaan_keamanan" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                            <option value="">-- Pilih Pertanyaan --</option>
                            <option value="Nama hewan peliharaan pertama Anda?">Nama hewan peliharaan pertama Anda?</option>
                            <option value="Nama SD tempat Anda bersekolah?">Nama SD tempat Anda bersekolah?</option>
                            <option value="Nama ibu kandung Anda?">Nama ibu kandung Anda?</option>
                            <option value="Kota kelahiran Anda?">Kota kelahiran Anda?</option>
                        </select>
                        @error('pertanyaan_keamanan')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Jawaban Keamanan</label>
                        <input type="text" name="jawaban_keamanan" value="{{ old('jawaban_keamanan') }}" placeholder="Tulis jawaban yang mudah Anda ingat"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        <p class="text-xs mt-1.5" style="color:rgba(30,58,95,0.45);">Digunakan saat lupa password.</p>
                        @error('jawaban_keamanan')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 px-7 py-4" style="background:rgba(30,58,95,0.02); border-top:1px solid rgba(30,58,95,0.06);">
                <a href="{{ route('user.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all"
                   style="background:#eef2ff; color:#1e3a5f; border:none;"
                   onmouseover="this.style.background='rgba(30,58,95,0.1)'"
                   onmouseout="this.style.background='#eef2ff'">Batal</a>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;"
                        onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                        onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                    <i class="bi bi-check2"></i> Simpan User
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.classList.replace('bi-eye','bi-eye-slash'); }
        else { input.type = 'password'; icon.classList.replace('bi-eye-slash','bi-eye'); }
    }
</script>
@endsection