<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Pande Hill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,500&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4" style="font-family:'DM Sans',sans-serif;">

<div class="w-full max-w-4xl flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="md:flex-1 flex flex-col p-8 md:p-10 relative overflow-hidden
                justify-center md:justify-between items-center md:items-stretch text-center md:text-left"
         style="background:#0b1527; min-height: 200px;">

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

        {{-- Badge — hidden di mobile --}}
        <div class="relative hidden md:block mb-0">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border"
                  style="background:rgba(212,175,55,0.12);border-color:rgba(212,175,55,0.25);color:#d4af37;">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Sistem Kasir Restoran
            </span>
        </div>

        {{-- Logo — selalu tampil --}}
        <div class="relative flex flex-col items-center">
            <div class="w-16 h-16 md:w-24 md:h-24 rounded-full flex items-center justify-center border"
                 style="background:rgba(212,175,55,0.06);border-color:rgba(212,175,55,0.2);">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     class="w-9 h-9 md:w-14 md:h-14 object-contain brightness-0 invert">
            </div>
            <h1 class="text-white font-semibold text-center mt-3 leading-snug text-lg md:text-2xl"
                style="font-family:'Playfair Display',serif;">
                The Pande Hill<br>Garden View
            </h1>
            <p class="text-xs mt-1.5 font-light tracking-widest uppercase" style="color:#4a6080;">Fine Dining · Bali</p>
            <div class="w-8 h-px mt-2 md:mt-3" style="background:linear-gradient(90deg,transparent,#d4af37,transparent);"></div>
        </div>

        {{-- Bottom text — hidden di mobile --}}
        <div class="relative hidden md:block">
            <h2 class="text-white text-3xl font-medium leading-tight" style="font-family:'Playfair Display',serif;">
                Selamat<br>Datang <span style="color:#d4af37;">Kembali!</span>
            </h2>
            <p class="text-sm mt-3 leading-relaxed font-light max-w-xs" style="color:#3d556e;">
                Kelola pesanan, menu, dan laporan restoran dengan mudah.
            </p>
            <p class="text-xs mt-8" style="color:#1e3050;">© {{ date('Y') }} The Pande Hill · All rights reserved.</p>
        </div>

    </div>

    {{-- ===== RIGHT PANEL ===== --}}
    <div class="w-full md:w-[380px] md:flex-shrink-0 bg-white flex flex-col justify-center px-6 py-8 md:px-10 md:py-10">

        {{-- Brand --}}
        <div class="flex items-center gap-2 mb-6 md:mb-8">
            <svg class="w-5 h-5" fill="none" stroke="#d4af37" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm font-semibold" style="color:#0b1527;">Pande Hill System</span>
        </div>

        <h2 class="text-xl font-semibold mb-1" style="color:#0b1527;">Masuk ke Akun</h2>
        <p class="text-sm text-slate-400 mb-6 md:mb-7">Silakan masukkan kredensial Anda</p>

        {{-- Error --}}
        @if(session('error'))
            <div class="flex items-start gap-2.5 bg-red-50 border border-red-100 text-red-500 text-xs rounded-xl px-4 py-3 mb-5">
                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Username --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}"
                           placeholder="Masukkan username" required
                           class="w-full border-[1.5px] border-slate-200 rounded-xl pl-9 pr-4 py-3 md:py-2.5 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                           style="font-family:'DM Sans',sans-serif;"
                           onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Password</label>
                    {{-- ✅ LINK LUPA PASSWORD --}}
                    <a href="{{ route('forgot.password') }}"
                       class="text-xs font-medium transition-colors"
                       style="color:#d4af37;"
                       onmouseover="this.style.color='#b8941f'"
                       onmouseout="this.style.color='#d4af37'">
                        Lupa password?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input type="password" name="password" id="passInput"
                           placeholder="••••••••" required
                           class="w-full border-[1.5px] border-slate-200 rounded-xl pl-9 pr-10 py-3 md:py-2.5 text-sm text-slate-800 placeholder-slate-300 outline-none transition-all"
                           style="font-family:'DM Sans',sans-serif;"
                           onfocus="this.style.borderColor='#d4af37';this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.12)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    <button type="button" onclick="togglePass()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <svg id="eyeIco" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full text-white font-semibold rounded-xl py-3 md:py-2.5 text-sm flex items-center justify-center gap-2 transition-all mt-2 active:scale-[0.98]"
                    style="background:#0b1527;font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#162540'"
                    onmouseout="this.style.background='#0b1527'">
                <span>Masuk Sekarang</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        {{-- Footer --}}
        <div class="flex items-center justify-between mt-6 md:mt-8 pt-5 border-t border-slate-100">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                <span class="text-xs text-slate-400">Sistem aktif</span>
            </div>
            <span class="text-xs text-slate-300">v1.0.0</span>
        </div>

        {{-- Copyright — tampil di mobile saja --}}
        <p class="text-center text-xs text-slate-300 mt-4 md:hidden">
            © {{ date('Y') }} The Pande Hill · All rights reserved.
        </p>
    </div>

</div>

<script>
    function togglePass() {
        const i = document.getElementById('passInput');
        const e = document.getElementById('eyeIco');
        if (i.type === 'password') {
            i.type = 'text';
            e.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
        } else {
            i.type = 'password';
            e.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        }
    }
</script>

</body>
</html>