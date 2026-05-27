<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - The Pande Hill</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,500&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4" style="font-family:'DM Sans',sans-serif;">

<div class="w-full max-w-4xl flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl">

    {{-- LEFT PANEL --}}
    <div class="hidden md:flex md:flex-1 flex-col justify-between p-10 relative overflow-hidden"
         style="background:#0b1527;">

        {{-- Decorative --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 rounded-full blur-3xl"
                 style="background:rgba(212,175,55,0.07);"></div>

            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 500 560" fill="none" preserveAspectRatio="xMidYMid slice">
                <circle cx="250" cy="280" r="200" stroke="rgba(212,175,55,0.06)" stroke-width="1"/>
                <circle cx="250" cy="280" r="290" stroke="rgba(212,175,55,0.04)" stroke-width="1"/>
                <circle cx="250" cy="280" r="380" stroke="rgba(212,175,55,0.025)" stroke-width="1"/>
            </svg>
        </div>

        <div class="relative">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border"
                  style="background:rgba(212,175,55,0.12);border-color:rgba(212,175,55,0.25);color:#d4af37;">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Pemulihan Akun
            </span>
        </div>

        <div class="relative flex flex-col items-center">
            <div class="w-24 h-24 rounded-full flex items-center justify-center border"
                 style="background:rgba(212,175,55,0.06);border-color:rgba(212,175,55,0.2);">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     class="w-14 h-14 object-contain brightness-0 invert">
            </div>

            <h1 class="text-white font-semibold text-center mt-3 leading-snug text-2xl"
                style="font-family:'Playfair Display',serif;">
                The Pande Hill<br>Garden View
            </h1>

            <p class="text-xs mt-1.5 font-light tracking-widest uppercase" style="color:#4a6080;">
                Fine Dining · Bali
            </p>

            <div class="w-8 h-px mt-3" style="background:linear-gradient(90deg,transparent,#d4af37,transparent);"></div>
        </div>

        <div class="relative">
            <h2 class="text-white text-3xl font-medium leading-tight" style="font-family:'Playfair Display',serif;">
                Reset<br>
                <span style="color:#d4af37;">Password Akun</span>
            </h2>

            <p class="text-sm mt-3 leading-relaxed font-light max-w-xs" style="color:#3d556e;">
                Verifikasi username dan pertanyaan keamanan untuk membuat password baru.
            </p>

            <p class="text-xs mt-8" style="color:#1e3050;">
                © {{ date('Y') }} The Pande Hill · All rights reserved.
            </p>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="w-full md:w-[420px] md:flex-shrink-0 bg-white flex flex-col justify-center px-6 py-8 md:px-10 md:py-10">

        {{-- Brand --}}
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-5 h-5" fill="none" stroke="#d4af37" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm font-semibold" style="color:#0b1527;">Pande Hill System</span>
        </div>

        <h2 class="text-2xl font-bold mb-1" style="color:#0b1527;">
            Reset Password
        </h2>

        <p class="text-sm text-slate-400 mb-6">
            @if(!session('reset_user_id'))
                Masukkan username untuk memulai pemulihan akun.
            @else
                Jawab pertanyaan keamanan dan buat password baru.
            @endif
        </p>

        {{-- Alert --}}
        @if(session('error'))
            <div class="flex items-start gap-2.5 bg-red-50 border border-red-100 text-red-500 text-xs rounded-xl px-4 py-3 mb-5">
                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="flex items-start gap-2.5 bg-green-50 border border-green-100 text-green-600 text-xs rounded-xl px-4 py-3 mb-5">
                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- STEP INDICATOR --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                     style="background:#0b1527;color:#fff;">
                    1
                </div>
                <span class="text-xs font-semibold" style="color:#0b1527;">Cek Username</span>
            </div>

            <div class="flex-1 h-px bg-slate-200"></div>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                     style="background:{{ session('reset_user_id') ? '#0b1527' : '#e2e8f0' }};color:{{ session('reset_user_id') ? '#fff' : '#94a3b8' }};">
                    2
                </div>
                <span class="text-xs font-semibold {{ session('reset_user_id') ? '' : 'text-slate-400' }}"
                      style="{{ session('reset_user_id') ? 'color:#0b1527;' : '' }}">
                    Reset
                </span>
            </div>
        </div>

        {{-- STEP 1 --}}
        @if(!session('reset_user_id'))
            <form action="{{ route('forgot.password.cek') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        Username
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>

                        <input type="text" name="username" value="{{ old('username') }}"
                               placeholder="Masukkan username Anda" required
                               class="w-full border-[1.5px] border-slate-200 rounded-xl pl-9 pr-4 py-3 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                               style="font-family:'DM Sans',sans-serif;"
                               onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                               onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                </div>

                <button type="submit"
                        class="w-full text-white font-semibold rounded-xl py-3 text-sm flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                        style="background:#0b1527;font-family:'DM Sans',sans-serif;"
                        onmouseover="this.style.background='#162540'"
                        onmouseout="this.style.background='#0b1527'">
                    <span>Cek Username</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        @else

            {{-- STEP 2 --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 mb-5">
                <p class="text-xs text-slate-400 mb-1">Pertanyaan keamanan</p>
                <p class="text-sm font-semibold" style="color:#0b1527;">
                    {{ session('pertanyaan_keamanan') }}
                </p>
            </div>

            <form action="{{ route('forgot.password.proses') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        Jawaban
                    </label>

                    <input type="text" name="jawaban"
                           placeholder="Masukkan jawaban keamanan" required
                           class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                           style="font-family:'DM Sans',sans-serif;"
                           onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        Password Baru
                    </label>

                    <input type="password" name="password_baru"
                           placeholder="Minimal 6 karakter" required minlength="6"
                           class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                           style="font-family:'DM Sans',sans-serif;"
                           onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        Konfirmasi Password Baru
                    </label>

                    <input type="password" name="password_konfirmasi"
                           placeholder="Ulangi password baru" required minlength="6"
                           class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                           style="font-family:'DM Sans',sans-serif;"
                           onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>

                <button type="submit"
                        class="w-full text-white font-semibold rounded-xl py-3 text-sm flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                        style="background:#16a34a;font-family:'DM Sans',sans-serif;"
                        onmouseover="this.style.background='#15803d'"
                        onmouseout="this.style.background='#16a34a'">
                    <span>Reset Password</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </form>
        @endif

        {{-- Back --}}
        <a href="{{ url('/login') }}"
           class="mt-5 w-full border border-slate-200 rounded-xl py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-all"
           style="color:#64748b;"
           onmouseover="this.style.borderColor='#d4af37';this.style.color='#0b1527';this.style.background='rgba(212,175,55,0.05)'"
           onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b';this.style.background='transparent'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Login
        </a>

        {{-- Footer --}}
        <div class="flex items-center justify-between mt-6 pt-5 border-t border-slate-100">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                <span class="text-xs text-slate-400">Sistem aktif</span>
            </div>
            <span class="text-xs text-slate-300">v1.0.0</span>
        </div>
    </div>
</div>

</body>
</html>