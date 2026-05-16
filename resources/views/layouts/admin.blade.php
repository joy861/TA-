<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Restoran - Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .admin-sidebar {
            background: #1e3a5f;
        }

        .admin-content-wrapper {
            transition: margin-left 0.3s ease;
        }

        .desktop-sidebar-toggle {
            background: rgba(30, 58, 95, 0.06);
            color: #1e3a5f;
            border: none;
        }

        .desktop-sidebar-toggle:hover {
            background: rgba(30, 58, 95, 0.12);
        }

        @media (min-width: 768px) {
            #sidebar.desktop-collapsed {
                transform: translateX(-100%) !important;
            }

            #contentWrapper.desktop-collapsed {
                margin-left: 0 !important;
            }
        }

        .sidebar-section-title {
            color: #bfdbfe !important;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-left: 12px;
            padding-right: 12px;
        }

        .sidebar-link {
            color: #dbeafe !important;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            text-decoration: none !important;
            position: relative;
        }

        .sidebar-link i,
        .sidebar-link span {
            color: inherit !important;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }

        .sidebar-link.active {
            background: #ffffff !important;
            color: #1e3a5f !important;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }

        .sidebar-link.active i,
        .sidebar-link.active span {
            color: #1e3a5f !important;
        }

        .sidebar-dot {
            margin-left: auto;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #60a5fa;
            flex-shrink: 0;
        }

        .sidebar-muted-text {
            color: rgba(255, 255, 255, 0.45);
        }

        .sidebar-user-role {
            color: rgba(255, 255, 255, 0.5);
        }

        .admin-breadcrumb {
    background: rgba(30, 58, 95, 0.06);
    border: 1px solid rgba(30, 58, 95, 0.08);
    box-shadow: 0 4px 12px rgba(30, 58, 95, 0.04);
}

        .admin-search-empty-row td {
            text-align: center;
            padding: 28px 16px !important;
            color: #94a3b8 !important;
            font-size: 14px;
            font-weight: 500;
        }

        .admin-search-clear {
            display: none;
        }

        .admin-search-clear.active {
            display: flex;
        }

        .admin-notification-dropdown {
            display: none;
            position: absolute;
            top: 44px;
            right: 0;
            width: 340px;
            background: #ffffff;
            border: 1px solid rgba(30, 58, 95, 0.08);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(30, 58, 95, 0.16);
            z-index: 999;
            overflow: hidden;
        }

        .admin-notification-dropdown.active {
            display: block;
        }

        .admin-notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #eef2ff;
        }

        .admin-notification-item {
            display: flex;
            gap: 12px;
            padding: 12px 14px;
            text-decoration: none;
            border-top: 1px solid rgba(30, 58, 95, 0.06);
            transition: background 0.2s ease;
        }

        .admin-notification-item:hover {
            background: #f8fafc;
        }

        .admin-notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-notification-empty {
            padding: 26px 18px;
            text-align: center;
            color: #94a3b8;
        }

        /* =========================
           MODAL LOGOUT ADMIN
        ========================= */
        .admin-logout-overlay {
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

        .admin-logout-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .admin-logout-box {
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

        .admin-logout-overlay.show .admin-logout-box {
            transform: translateY(0) scale(1);
        }

        .admin-logout-box::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: rgba(239, 68, 68, 0.08);
            top: -90px;
            right: -90px;
        }

        .admin-logout-icon {
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

        .admin-logout-title {
            font-size: 28px;
            font-weight: 900;
            color: #1e3a5f;
            margin-bottom: 10px;
            letter-spacing: -0.04em;
            position: relative;
            z-index: 2;
        }

        .admin-logout-text {
            font-size: 16px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .admin-logout-subtext {
            font-size: 14px;
            color: #7188a7;
            line-height: 1.6;
            margin-bottom: 26px;
            position: relative;
            z-index: 2;
        }

        .admin-logout-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .admin-logout-btn {
            border: none;
            border-radius: 16px;
            padding: 13px 20px;
            min-width: 120px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-logout-cancel {
            background: #eef2f7;
            color: #475569;
        }

        .admin-logout-cancel:hover {
            background: #e2e8f0;
        }

        .admin-logout-submit {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            color: #ffffff;
            box-shadow: 0 14px 30px rgba(239, 68, 68, 0.24);
        }

        .admin-logout-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 35px rgba(239, 68, 68, 0.30);
        }

        @media (max-width: 576px) {
            .admin-logout-box {
                padding: 28px 20px 22px;
                border-radius: 22px;
            }

            .admin-logout-title {
                font-size: 24px;
            }

            .admin-logout-actions {
                flex-direction: column-reverse;
            }

            .admin-logout-btn {
                width: 100%;
            }
        }

    </style>
</head>

<body class="min-h-screen" style="background:#eef2ff;">

{{-- TOAST --}}
<div id="toastContainer" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

@if(session('success'))

{{-- MODAL KONFIRMASI LOGOUT ADMIN --}}
<div id="adminLogoutModal" class="admin-logout-overlay">
    <div class="admin-logout-box">
        <div class="admin-logout-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>

        <h3 class="admin-logout-title">Keluar Sistem?</h3>

        <p class="admin-logout-text">
            Yakin ingin logout dari akun admin ini?
        </p>

        <p class="admin-logout-subtext">
            Kamu bisa login kembali kapan saja menggunakan akun admin.
        </p>

        <div class="admin-logout-actions">
            <button type="button" class="admin-logout-btn admin-logout-cancel" onclick="closeAdminLogoutModal()">
                Batal
            </button>

            <button type="button" class="admin-logout-btn admin-logout-submit" onclick="submitAdminLogout()">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<script>

    function openAdminLogoutModal() {
        const modal = document.getElementById('adminLogoutModal');

        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAdminLogoutModal() {
        const modal = document.getElementById('adminLogoutModal');

        if (modal) {
            modal.classList.remove('show');
        }

        document.body.style.overflow = '';
    }

    function submitAdminLogout() {
        const form = document.getElementById('adminLogoutForm');

        if (form) {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', () => showToast('success', @json(session('success'))));
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', () => showToast('error', @json(session('error'))));
</script>
@endif

{{-- OVERLAY --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside id="sidebar"
       class="admin-sidebar fixed top-0 left-0 h-full w-60 flex flex-col z-50 transition-transform duration-300 -translate-x-full md:translate-x-0">

    {{-- Brand --}}
    <div class="h-16 px-6 flex items-center" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-2 no-underline">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0"
     style="background:rgba(255,255,255,0.12);">
    <img src="{{ asset('images/logo.png') }}"
         alt="Logo Pande Hill"
         class="w-full h-full object-contain p-1">
</div>

<div>
    <div class="text-sm font-bold text-white leading-tight">Pande Hill</div>
    <div class="text-xs font-medium sidebar-muted-text" style="letter-spacing:0.08em;">
        ADMIN PANEL
    </div>
</div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">

        <div class="sidebar-section-title">Overview</div>

        <a href="{{ url('admin/dashboard') }}"
           class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 text-base w-4 text-center"></i>
            <span>Dashboard</span>

            @if(request()->is('admin/dashboard'))
                <span class="sidebar-dot"></span>
            @endif
        </a>

        <div class="sidebar-section-title mt-5">Manajemen</div>

        @php
            $navItems = [
                [
                    'route' => 'user.index',
                    'pattern' => 'user.*',
                    'icon' => 'bi-people',
                    'label' => 'User'
                ],
                [
                    'route' => 'kategori.index',
                    'pattern' => 'kategori.*',
                    'icon' => 'bi-tags',
                    'label' => 'Kategori'
                ],
                [
                    'route' => 'menu.index',
                    'pattern' => 'menu.*',
                    'icon' => 'bi-journal-text',
                    'label' => 'Menu'
                ],
                [
                    'route' => 'meja.index',
                    'pattern' => 'meja.*',
                    'icon' => 'bi-grid-3x3-gap',
                    'label' => 'Meja'
                ],
            ];
        @endphp

        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="sidebar-link {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                <i class="bi {{ $item['icon'] }} text-base w-4 text-center"></i>
                <span>{{ $item['label'] }}</span>

                @if(request()->routeIs($item['pattern']))
                    <span class="sidebar-dot"></span>
                @endif
            </a>
        @endforeach

        <div class="sidebar-section-title mt-5">Analisis</div>

        <a href="{{ route('laporan.index') }}"
           class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart text-base w-4 text-center"></i>
            <span>Laporan</span>

            @if(request()->routeIs('laporan.*'))
                <span class="sidebar-dot"></span>
            @endif
        </a>
    </nav>

    {{-- Footer --}}
    <div class="px-4 py-4" style="border-top:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold flex-shrink-0"
                 style="background:#60a5fa; color:#1e3a5f;">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate m-0">
                    {{ Auth::user()->nama }}
                </p>
                <p class="text-xs capitalize m-0 sidebar-user-role">
                    {{ Auth::user()->role }}
                </p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
            class="w-full text-xs font-semibold py-2 rounded-xl flex items-center justify-center gap-2 transition-all"
            style="background:rgba(255,255,255,0.08); color:#dbeafe; border:none;"
            onmouseover="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'"
            onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#dbeafe'">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </button>
</form>
    </div>
</aside>

{{-- WRAPPER --}}
<div id="contentWrapper" class="admin-content-wrapper md:ml-60 flex flex-col min-h-screen">

    {{-- TOPBAR --}}
    <header class="sticky top-0 z-30 h-16 flex items-center justify-between px-5 md:px-7"
        style="background:rgba(238,242,255,0.9); backdrop-filter:blur(12px); border-bottom:1px solid rgba(30,58,95,0.08);">

        <div class="flex items-center gap-3">
            {{-- MOBILE SIDEBAR TOGGLE --}}
            <button class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg"
                    style="color:#1e3a5f;" onclick="toggleSidebar()">
                <i class="bi bi-list text-xl"></i>
            </button>

            {{-- DESKTOP SIDEBAR TOGGLE --}}
            <button type="button"
                    id="desktopSidebarToggle"
                    class="desktop-sidebar-toggle hidden md:flex w-8 h-8 items-center justify-center rounded-lg transition-all"
                    onclick="toggleDesktopSidebar()"
                    title="Buka/Tutup Sidebar">
                <i id="desktopSidebarIcon" class="bi bi-layout-sidebar-inset text-lg"></i>
            </button>

            @php
                $isDashboardPage = request()->is('admin/dashboard') || request()->routeIs('admin.dashboard');

                if ($isDashboardPage) {
                    $breadcrumbTitle = 'Dashboard';
                } elseif (request()->routeIs('user.*')) {
                    $breadcrumbTitle = 'User';
                } elseif (request()->routeIs('kategori.*')) {
                    $breadcrumbTitle = 'Kategori';
                } elseif (request()->routeIs('menu.*')) {
                    $breadcrumbTitle = 'Menu';
                } elseif (request()->routeIs('meja.*')) {
                    $breadcrumbTitle = 'Meja';
                } elseif (request()->routeIs('laporan.*')) {
                    $breadcrumbTitle = 'Laporan';
                } else {
                    $breadcrumbTitle = 'Admin';
                }
            @endphp

            {{-- BREADCRUMB --}}
<div class="admin-breadcrumb hidden sm:flex items-center gap-2.5 px-4 py-2.5 rounded-2xl">
    <span class="text-sm font-semibold" style="color:rgba(30,58,95,0.55);">
        Admin
    </span>

    <i class="bi bi-chevron-right text-xs" style="color:rgba(30,58,95,0.4);"></i>

    <span class="text-base font-bold" style="color:#1e3a5f;">
        {{ $breadcrumbTitle }}
    </span>
</div>

            @if(!$isDashboardPage)
                {{-- SEARCH AKTIF, MUNCUL SELAIN DASHBOARD --}}
                <div class="relative hidden lg:block">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-xs"
                       style="color:rgba(30,58,95,0.35);"></i>

                    <input type="text"
                           id="adminSearchInput"
                           autocomplete="off"
                           placeholder="Cari di halaman ini..."
                           class="pl-8 pr-9 py-2 text-sm rounded-xl outline-none w-56 transition-all"
                           style="background:rgba(30,58,95,0.06); color:#1e3a5f; border:1.5px solid transparent;"
                           onfocus="this.style.borderColor='#60a5fa';this.style.background='#fff'"
                           onblur="this.style.borderColor='transparent';this.style.background='rgba(30,58,95,0.06)'">

                    <button type="button"
                            id="adminSearchClear"
                            class="admin-search-clear absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-lg items-center justify-center"
                            style="background:rgba(30,58,95,0.08); color:#1e3a5f;"
                            onclick="clearAdminSearch()">
                        <i class="bi bi-x text-sm"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @php
                $pendingPesanan = 0;
                $mejaTerisi = 0;
                $menuTidakTersedia = 0;

                $pesananTable = null;
                if (\Illuminate\Support\Facades\Schema::hasTable('pesanans')) {
                    $pesananTable = 'pesanans';
                } elseif (\Illuminate\Support\Facades\Schema::hasTable('pesanan')) {
                    $pesananTable = 'pesanan';
                }

                if ($pesananTable && \Illuminate\Support\Facades\Schema::hasColumn($pesananTable, 'status')) {
                    $pendingPesanan = \Illuminate\Support\Facades\DB::table($pesananTable)
                        ->whereNotIn('status', ['sudah_bayar', 'selesai', 'batal', 'dibatalkan'])
                        ->count();
                }

                $mejaTable = null;
                if (\Illuminate\Support\Facades\Schema::hasTable('mejas')) {
                    $mejaTable = 'mejas';
                } elseif (\Illuminate\Support\Facades\Schema::hasTable('meja')) {
                    $mejaTable = 'meja';
                }

                if ($mejaTable && \Illuminate\Support\Facades\Schema::hasColumn($mejaTable, 'status')) {
                    $mejaTerisi = \Illuminate\Support\Facades\DB::table($mejaTable)
                        ->where('status', 'terisi')
                        ->count();
                }

                $menuTable = null;
                if (\Illuminate\Support\Facades\Schema::hasTable('menus')) {
                    $menuTable = 'menus';
                } elseif (\Illuminate\Support\Facades\Schema::hasTable('menu')) {
                    $menuTable = 'menu';
                }

                if ($menuTable && \Illuminate\Support\Facades\Schema::hasColumn($menuTable, 'status')) {
                    $menuTidakTersedia = \Illuminate\Support\Facades\DB::table($menuTable)
                        ->where('status', '!=', 'tersedia')
                        ->count();
                }

                $notificationCount = $pendingPesanan + $mejaTerisi + $menuTidakTersedia;

                $pesananUrl = \Illuminate\Support\Facades\Route::has('laporan.index') ? route('laporan.index') : url('admin/dashboard');
                $mejaUrl = \Illuminate\Support\Facades\Route::has('meja.index') ? route('meja.index') : url('admin/dashboard');
                $menuUrl = \Illuminate\Support\Facades\Route::has('menu.index') ? route('menu.index') : url('admin/dashboard');
            @endphp

            {{-- NOTIFIKASI ADMIN --}}
            <div id="adminNotificationWrapper" class="relative">
                <button type="button"
                        class="w-8 h-8 rounded-xl flex items-center justify-center relative transition-all"
                        style="background:rgba(30,58,95,0.06);"
                        onclick="toggleAdminNotification()"
                        onmouseover="this.style.background='rgba(30,58,95,0.1)'"
                        onmouseout="this.style.background='rgba(30,58,95,0.06)'"
                        title="Notifikasi">
                    <i class="bi bi-bell text-sm" style="color:#1e3a5f;"></i>

                    @if($notificationCount > 0)
                        <span class="admin-notification-badge">
                            {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                        </span>
                    @else
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full"
                              style="background:#60a5fa;"></span>
                    @endif
                </button>

                <div id="adminNotificationDropdown" class="admin-notification-dropdown">
                    <div class="px-4 py-3 flex items-center justify-between"
                         style="background:#f8fafc; border-bottom:1px solid rgba(30,58,95,0.06);">
                        <div>
                            <p class="m-0 text-sm font-bold" style="color:#1e3a5f;">Notifikasi</p>
                            <p class="m-0 text-xs" style="color:#94a3b8;">
                                Ringkasan aktivitas sistem
                            </p>
                        </div>

                        @if($notificationCount > 0)
                            <span class="text-xs font-bold px-2 py-1 rounded-lg"
                                  style="background:#dbeafe; color:#1e3a5f;">
                                {{ $notificationCount }} baru
                            </span>
                        @endif
                    </div>

                    @if($notificationCount > 0)
                        @if($pendingPesanan > 0)
                            <a href="{{ $pesananUrl }}" class="admin-notification-item">
                                <div class="admin-notification-icon" style="background:#fee2e2; color:#ef4444;">
                                    <i class="bi bi-receipt-cutoff"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="m-0 text-sm font-bold" style="color:#1e3a5f;">
                                        {{ $pendingPesanan }} pesanan belum dibayar
                                    </p>
                                    <p class="m-0 text-xs" style="color:#94a3b8;">
                                        Periksa pesanan yang masih pending.
                                    </p>
                                </div>
                            </a>
                        @endif

                        @if($mejaTerisi > 0)
                            <a href="{{ $mejaUrl }}" class="admin-notification-item">
                                <div class="admin-notification-icon" style="background:#dbeafe; color:#2563eb;">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="m-0 text-sm font-bold" style="color:#1e3a5f;">
                                        {{ $mejaTerisi }} meja masih terisi
                                    </p>
                                    <p class="m-0 text-xs" style="color:#94a3b8;">
                                        Ada meja yang belum kembali tersedia.
                                    </p>
                                </div>
                            </a>
                        @endif

                        @if($menuTidakTersedia > 0)
                            <a href="{{ $menuUrl }}" class="admin-notification-item">
                                <div class="admin-notification-icon" style="background:#fef3c7; color:#d97706;">
                                    <i class="bi bi-journal-x"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="m-0 text-sm font-bold" style="color:#1e3a5f;">
                                        {{ $menuTidakTersedia }} menu tidak tersedia
                                    </p>
                                    <p class="m-0 text-xs" style="color:#94a3b8;">
                                        Periksa status menu pada halaman menu.
                                    </p>
                                </div>
                            </a>
                        @endif
                    @else
                        <div class="admin-notification-empty">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-2xl flex items-center justify-center"
                                 style="background:#f1f5f9; color:#94a3b8;">
                                <i class="bi bi-bell-slash text-xl"></i>
                            </div>
                            <p class="m-0 text-sm font-bold" style="color:#1e3a5f;">
                                Belum ada notifikasi
                            </p>
                            <p class="m-0 text-xs mt-1" style="color:#94a3b8;">
                                Semua aktivitas sistem masih aman.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 pl-2"
                 style="border-left:1px solid rgba(30,58,95,0.1);">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                     style="background:#1e3a5f; color:#60a5fa;">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>

                <div class="hidden sm:block">
                    <p class="text-xs font-bold leading-tight m-0" style="color:#1e3a5f;">
                        {{ Auth::user()->nama }}
                    </p>
                    <p class="text-xs capitalize leading-tight m-0" style="color:rgba(30,58,95,0.45);">
                        {{ Auth::user()->role }}
                    </p>
                </div>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-1 p-4 md:p-7">
        @yield('content')
    </main>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }

    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('sidebar');
        const contentWrapper = document.getElementById('contentWrapper');
        const icon = document.getElementById('desktopSidebarIcon');

        if (!sidebar || !contentWrapper) return;

        sidebar.classList.toggle('desktop-collapsed');
        contentWrapper.classList.toggle('desktop-collapsed');

        const isCollapsed = sidebar.classList.contains('desktop-collapsed');

        if (icon) {
            icon.className = isCollapsed
                ? 'bi bi-layout-sidebar text-lg'
                : 'bi bi-layout-sidebar-inset text-lg';
        }

        localStorage.setItem('adminSidebarCollapsed', isCollapsed ? 'true' : 'false');
    }

    function restoreDesktopSidebarState() {
        const sidebar = document.getElementById('sidebar');
        const contentWrapper = document.getElementById('contentWrapper');
        const icon = document.getElementById('desktopSidebarIcon');

        const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';

        if (isCollapsed && sidebar && contentWrapper) {
            sidebar.classList.add('desktop-collapsed');
            contentWrapper.classList.add('desktop-collapsed');

            if (icon) {
                icon.className = 'bi bi-layout-sidebar text-lg';
            }
        }
    }

    function toggleAdminNotification() {
        const dropdown = document.getElementById('adminNotificationDropdown');

        if (!dropdown) return;

        dropdown.classList.toggle('active');
    }

    function closeAdminNotification() {
        const dropdown = document.getElementById('adminNotificationDropdown');

        if (!dropdown) return;

        dropdown.classList.remove('active');
    }

    function showToast(type, message) {
        const container = document.getElementById('toastContainer');

        const colors = {
            success: {
                border: 'rgba(34,197,94,0.25)',
                icon: 'bi-check-circle-fill',
                iconColor: '#22c55e',
                bar: '#22c55e'
            },
            error: {
                border: 'rgba(239,68,68,0.25)',
                icon: 'bi-exclamation-circle-fill',
                iconColor: '#ef4444',
                bar: '#ef4444'
            },
            warning: {
                border: 'rgba(96,165,250,0.35)',
                icon: 'bi-exclamation-triangle-fill',
                iconColor: '#60a5fa',
                bar: '#60a5fa'
            },
        };

        const c = colors[type] || colors.success;
        const toast = document.createElement('div');

        toast.className = 'pointer-events-auto';
        toast.style.cssText = `
            display:flex;
            align-items:flex-start;
            gap:12px;
            background:#ffffff;
            border:1px solid ${c.border};
            border-radius:14px;
            padding:14px 16px;
            min-width:300px;
            max-width:380px;
            box-shadow:0 8px 32px rgba(30,58,95,0.12);
            transform:translateX(120%);
            transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1),opacity 0.3s ease;
            opacity:0;
            position:relative;
            overflow:hidden;
            font-family:'Inter',sans-serif;
        `;

        toast.innerHTML = `
            <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;background:${c.iconColor}18;display:flex;align-items:center;justify-content:center;">
                <i class="bi ${c.icon}" style="color:${c.iconColor};font-size:15px;"></i>
            </div>

            <div style="flex:1;min-width:0;padding-top:2px;">
                <p style="font-size:13px;font-weight:600;color:#1e3a5f;margin:0 0 2px;">
                    ${message}
                </p>
                <p style="font-size:11px;color:rgba(30,58,95,0.4);margin:0;">
                    Sistem Restoran
                </p>
            </div>

            <button onclick="dismissToast(this.closest('.pointer-events-auto'))"
                    style="background:none;border:none;cursor:pointer;color:rgba(30,58,95,0.3);padding:2px;flex-shrink:0;font-size:12px;"
                    onmouseover="this.style.color='rgba(30,58,95,0.7)'"
                    onmouseout="this.style.color='rgba(30,58,95,0.3)'">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="toast-bar"
                 style="position:absolute;bottom:0;left:0;height:3px;width:100%;background:${c.bar};border-radius:0 0 14px 14px;transform-origin:left;">
            </div>
        `;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 10);
        });

        setTimeout(() => {
            const bar = toast.querySelector('.toast-bar');
            bar.style.transition = 'transform 3.2s linear';
            bar.style.transform = 'scaleX(0)';
        }, 50);

        setTimeout(() => dismissToast(toast), 3700);
    }

    function dismissToast(toast) {
        if (!toast || toast._dismissing) return;

        toast._dismissing = true;
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';

        setTimeout(() => toast.remove(), 400);
    }

    function normalizeText(text) {
        return text.toLowerCase().replace(/\s+/g, ' ').trim();
    }

    function removeSearchEmptyRows() {
        document.querySelectorAll('.admin-search-empty-row').forEach(function (row) {
            row.remove();
        });
    }

    function clearAdminSearch() {
        const input = document.getElementById('adminSearchInput');
        const clearButton = document.getElementById('adminSearchClear');

        if (!input) return;

        input.value = '';
        if (clearButton) clearButton.classList.remove('active');

        runAdminSearch('');
        input.focus();
    }

    function runAdminSearch(keyword) {
        const searchValue = normalizeText(keyword);
        const clearButton = document.getElementById('adminSearchClear');

        if (clearButton) {
            if (searchValue.length > 0) {
                clearButton.classList.add('active');
            } else {
                clearButton.classList.remove('active');
            }
        }

        removeSearchEmptyRows();

        const tables = document.querySelectorAll('main table');

        tables.forEach(function (table) {
            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr')).filter(function (row) {
                return !row.classList.contains('admin-search-empty-row');
            });

            let visibleCount = 0;

            rows.forEach(function (row) {
                const rowText = normalizeText(row.innerText);

                if (searchValue === '' || rowText.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (searchValue !== '' && rows.length > 0 && visibleCount === 0) {
                const colCount = table.querySelectorAll('thead th').length || 1;
                const emptyRow = document.createElement('tr');
                emptyRow.className = 'admin-search-empty-row';
                emptyRow.innerHTML = `
                    <td colspan="${colCount}">
                        <i class="bi bi-search mr-1"></i>
                        Data tidak ditemukan untuk pencarian "${keyword}"
                    </td>
                `;
                tbody.appendChild(emptyRow);
            }
        });

        const searchableCards = document.querySelectorAll('[data-search-item]');

        searchableCards.forEach(function (item) {
            const itemText = normalizeText(item.innerText);

            if (searchValue === '' || itemText.includes(searchValue)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.addEventListener('click', function (event) {
        const wrapper = document.getElementById('adminNotificationWrapper');

        if (wrapper && !wrapper.contains(event.target)) {
            closeAdminNotification();
        }

        const logoutModal = document.getElementById('adminLogoutModal');

        if (logoutModal && event.target === logoutModal) {
            closeAdminLogoutModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAdminNotification();
            closeAdminLogoutModal();
            clearAdminSearch();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        restoreDesktopSidebarState();

        const input = document.getElementById('adminSearchInput');

        if (!input) return;

        input.addEventListener('input', function () {
            runAdminSearch(this.value);
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                clearAdminSearch();
            }
        });
    });
</script>

</body>
</html>