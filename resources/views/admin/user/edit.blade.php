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

    <form action="{{ route('user.update', $user->id_user) }}" method="POST">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid rgba(30,58,95,0.08);">

            <div class="flex items-center gap-4 px-7 py-5" style="border-bottom:1px solid rgba(30,58,95,0.06); background:#1e3a5f;">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg font-black flex-shrink-0"
                     style="background:#60a5fa; color:#1e3a5f;">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-black" style="color:#fff; letter-spacing:-0.3px;">Edit User</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs font-mono" style="color:rgba(255,255,255,0.5);">{{ $user->username }}</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                              style="{{ $user->role=='admin' ? 'background:#60a5fa; color:#1e3a5f;' : 'background:rgba(255,255,255,0.15); color:#fff;' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-7 py-6">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">INFORMASI AKUN</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('nama')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('username')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Password <span class="text-xs font-normal" style="color:rgba(30,58,95,0.4);">(opsional)</span></label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="••••••••"
                                   class="w-full rounded-xl px-4 py-2.5 pr-10 text-sm outline-none transition-all"
                                   style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                                   onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                   onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2"
                                    style="color:rgba(30,58,95,0.4); background:none; border:none; cursor:pointer;">
                                <i class="bi bi-eye text-base"></i>
                            </button>
                        </div>
                        <p class="text-xs mt-1.5" style="color:rgba(30,58,95,0.45);">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Role</label>
                        <select name="role" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                            <option value="admin" {{ old('role',$user->role)=='admin'?'selected':'' }}>Admin</option>
                            <option value="kasir" {{ old('role',$user->role)=='kasir'?'selected':'' }}>Kasir</option>
                        </select>
                        @error('role')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="px-7 py-6" style="border-top:1px solid rgba(30,58,95,0.04);">
                <p class="text-xs font-bold tracking-widest uppercase mb-4" style="color:#60a5fa; letter-spacing:0.15em;">KEAMANAN AKUN</p>
                <div class="space-y-4">
                    @php
                        $pertanyaanList = ['Nama hewan peliharaan pertama Anda?','Nama SD tempat Anda bersekolah?','Nama ibu kandung Anda?','Kota kelahiran Anda?'];
                        $selectedPertanyaan = old('pertanyaan_keamanan', $user->pertanyaan_keamanan);
                    @endphp
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Pertanyaan Keamanan</label>
                        <select name="pertanyaan_keamanan" class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all appearance-none"
                                style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231e3a5f' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 1rem center; padding-right:2.5rem;"
                                onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                                onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                            <option value="">-- Pilih Pertanyaan --</option>
                            @foreach($pertanyaanList as $p)
                                <option value="{{ $p }}" {{ $selectedPertanyaan==$p?'selected':'' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pertanyaan_keamanan')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5" style="color:#1e3a5f;">Jawaban Keamanan</label>
                        <input type="text" name="jawaban_keamanan" value="{{ old('jawaban_keamanan', $user->jawaban_keamanan) }}"
                               class="w-full rounded-xl px-4 py-2.5 text-sm outline-none transition-all"
                               style="border:1.5px solid rgba(30,58,95,0.12); color:#1e3a5f;"
                               onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 3px rgba(96,165,250,0.12)'"
                               onblur="this.style.borderColor='rgba(30,58,95,0.12)';this.style.boxShadow='none'" required>
                        @error('jawaban_keamanan')<p class="text-xs mt-1 text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 px-7 py-4" style="background:rgba(30,58,95,0.02); border-top:1px solid rgba(30,58,95,0.06);">
                <a href="{{ route('user.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl"
                   style="background:#eef2ff; color:#1e3a5f; border:none;"
                   onmouseover="this.style.background='rgba(30,58,95,0.1)'"
                   onmouseout="this.style.background='#eef2ff'">Batal</a>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl transition-all"
                        style="background:#1e3a5f; color:#fff; border:none;"
                        onmouseover="this.style.background='#60a5fa';this.style.color='#1e3a5f'"
                        onmouseout="this.style.background='#1e3a5f';this.style.color='#fff'">
                    <i class="bi bi-check2"></i> Simpan Perubahan
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