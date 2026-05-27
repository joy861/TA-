<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kasir' }} - Pande Hill</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #eef2ff;
            color: #1e3a5f;
            margin: 0;
        }

        .kasir-topbar {
            background: #1e3a5f;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .kasir-brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #60a5fa;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: -0.5px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .kasir-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kasir-brand-title {
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.4px;
            line-height: 1.1;
        }

        .kasir-brand-subtitle {
            color: rgba(255,255,255,0.45);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .kasir-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        .kasir-nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }

        .kasir-nav-link.active {
            color: #1e3a5f;
            background: #fff;
        }

        .kasir-nav-link.active::after {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #60a5fa;
            margin-left: 4px;
        }

        .kasir-nav-link i {
            font-size: 14px;
        }

        .kasir-user-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px 6px 6px;
            border-radius: 12px;
            background: rgba(255,255,255,0.06);
        }

        .kasir-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #60a5fa;
            color: #1e3a5f;
            font-size: 13px;
            font-weight: 900;
            flex-shrink: 0;
        }

        .kasir-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .kasir-page-eyebrow {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #60a5fa;
            margin-bottom: 6px;
        }

        .kasir-page-title {
            font-size: 30px;
            line-height: 1.1;
            font-weight: 900;
            color: #1e3a5f;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .kasir-page-subtitle {
            margin-top: 6px;
            color: rgba(30,58,95,0.5);
            font-size: 14px;
            font-weight: 500;
        }

        .kasir-card {
            background: #fff;
            border: 1px solid rgba(30,58,95,0.08);
            border-radius: 20px;
        }

        .kasir-card-soft {
            background: #fff;
            border: 1px solid rgba(30,58,95,0.08);
            border-radius: 16px;
            transition: all 0.2s ease;
        }

        .kasir-card-soft:hover {
            border-color: #60a5fa;
            box-shadow: 0 8px 24px rgba(30,58,95,0.06);
        }

        .kasir-stat-card {
            border-radius: 20px;
            padding: 20px;
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fff;
            border: 1px solid rgba(30,58,95,0.08);
        }

        .kasir-stat-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(30,58,95,0.4);
            margin-bottom: 4px;
        }

        .kasir-stat-value {
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
            color: #1e3a5f;
            letter-spacing: -1.2px;
        }

        .kasir-stat-note {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(30,58,95,0.45);
        }

        .kasir-stat-primary {
            background: #1e3a5f;
            border-color: #1e3a5f;
        }

        .kasir-stat-primary .kasir-stat-label {
            color: rgba(255,255,255,0.45);
        }

        .kasir-stat-primary .kasir-stat-value {
            color: #fff;
        }

        .kasir-stat-primary .kasir-stat-note {
            color: rgba(255,255,255,0.55);
        }

        .kasir-stat-accent {
            background: #60a5fa;
            border-color: #60a5fa;
        }

        .kasir-stat-accent .kasir-stat-label,
        .kasir-stat-accent .kasir-stat-note {
            color: rgba(30,58,95,0.6);
        }

        .kasir-stat-accent .kasir-stat-value {
            color: #1e3a5f;
        }

        .kasir-section-title {
            font-size: 18px;
            font-weight: 900;
            color: #1e3a5f;
            letter-spacing: -0.3px;
            margin: 0;
        }

        .kasir-section-subtitle {
            color: rgba(30,58,95,0.45);
            font-size: 13px;
            font-weight: 500;
            margin-top: 2px;
        }

        .kasir-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 42px;
            padding: 0 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .kasir-btn-primary {
            background: #1e3a5f;
            color: #fff;
        }

        .kasir-btn-primary:hover {
            background: #60a5fa;
            color: #1e3a5f;
        }

        .kasir-btn-success {
            background: #16a34a;
            color: #fff;
        }

        .kasir-btn-success:hover {
            background: #15803d;
            color: #fff;
        }

        .kasir-btn-outline {
            background: #fff;
            color: #1e3a5f;
            border-color: rgba(30,58,95,0.15);
        }

        .kasir-btn-outline:hover {
            background: #1e3a5f;
            color: #fff;
            border-color: #1e3a5f;
        }

        .kasir-btn-ghost {
            background: #eef2ff;
            color: #1e3a5f;
            border: none;
        }

        .kasir-btn-ghost:hover {
            background: #1e3a5f;
            color: #fff;
        }

        .kasir-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .kasir-badge-success {
            background: rgba(34,197,94,0.1);
            color: #15803d;
        }

        .kasir-badge-warning {
            background: rgba(245,158,11,0.1);
            color: #b45309;
        }

        .kasir-badge-info {
            background: #eef2ff;
            color: #1e3a5f;
        }

        .kasir-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .kasir-table-wrap {
            overflow-x: auto;
        }

        .kasir-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
            background: #fff;
        }

        .kasir-table thead th {
            background: rgba(30,58,95,0.02);
            color: rgba(30,58,95,0.4);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 13px 18px;
            border-bottom: 1px solid rgba(30,58,95,0.06);
            text-align: left;
        }

        .kasir-table tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(30,58,95,0.04);
            color: #1e3a5f;
            font-size: 14px;
            vertical-align: middle;
        }

        .kasir-table tbody tr:hover td {
            background: rgba(30,58,95,0.02);
        }

        .kasir-table tbody tr:last-child td {
            border-bottom: none;
        }

        .kasir-empty-state {
            text-align: center;
            padding: 48px 20px;
            color: rgba(30,58,95,0.4);
        }

        .kasir-empty-state i {
            font-size: 36px;
            color: rgba(30,58,95,0.2);
            margin-bottom: 12px;
            display: block;
        }

        .kasir-alert {
            border-radius: 14px;
            border: 1px solid;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kasir-alert-success {
            background: rgba(34,197,94,0.08);
            border-color: rgba(34,197,94,0.25);
            color: #15803d;
        }

        .kasir-alert-error {
            background: rgba(239,68,68,0.08);
            border-color: rgba(239,68,68,0.25);
            color: #b91c1c;
        }

        .kasir-toast-container {
            position: fixed;
            top: 86px;
            left: 18px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: min(380px, calc(100vw - 36px));
            pointer-events: none;
        }

        .kasir-toast {
            pointer-events: auto;
            display: grid;
            grid-template-columns: 34px 1fr auto;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 14px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 18px 45px rgba(8, 20, 43, 0.18);
            transform: translateX(-18px);
            opacity: 0;
            animation: kasirToastIn 0.35s ease forwards;
        }

        .kasir-toast.hide {
            animation: kasirToastOut 0.35s ease forwards;
        }

        .kasir-toast-icon {
            width: 34px;
            height: 34px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }

        .kasir-toast-title {
            font-size: 13px;
            font-weight: 900;
            color: #1e3a5f;
            line-height: 1.25;
            margin-bottom: 3px;
        }

        .kasir-toast-message {
            font-size: 12px;
            font-weight: 650;
            color: rgba(30,58,95,0.6);
            line-height: 1.45;
        }

        .kasir-toast-close {
            width: 26px;
            height: 26px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: rgba(30,58,95,0.35);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .kasir-toast-close:hover {
            background: rgba(30,58,95,0.06);
            color: #1e3a5f;
        }

        .kasir-toast-success {
            border-left: 5px solid #16a34a;
        }

        .kasir-toast-success .kasir-toast-icon {
            background: rgba(34,197,94,0.12);
            color: #16a34a;
        }

        .kasir-toast-error {
            border-left: 5px solid #ef4444;
        }

        .kasir-toast-error .kasir-toast-icon {
            background: rgba(239,68,68,0.12);
            color: #dc2626;
        }

        @keyframes kasirToastIn {
            from {
                transform: translateX(-18px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes kasirToastOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-18px);
                opacity: 0;
            }
        }

        @media (max-width: 576px) {
            .kasir-toast-container {
                top: 78px;
                left: 12px;
                width: calc(100vw - 24px);
            }

            .kasir-toast {
                grid-template-columns: 32px 1fr auto;
                padding: 13px;
            }
        }


        .kasir-mini-list-item + .kasir-mini-list-item {
            border-top: 1px solid rgba(30,58,95,0.06);
        }

        .kasir-mobile-nav {
            display: none;
        }

        .logout-modal-overlay {
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

        .logout-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .logout-modal-box {
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

        .logout-modal-overlay.show .logout-modal-box {
            transform: translateY(0) scale(1);
        }

        .logout-modal-box::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: rgba(239, 68, 68, 0.08);
            top: -90px;
            right: -90px;
        }

        .logout-modal-icon {
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

        .logout-modal-title {
            font-size: 28px;
            font-weight: 900;
            color: #1e3a5f;
            margin-bottom: 10px;
            letter-spacing: -0.04em;
            position: relative;
            z-index: 2;
        }

        .logout-modal-text {
            font-size: 16px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .logout-modal-subtext {
            font-size: 14px;
            color: #7188a7;
            line-height: 1.6;
            margin-bottom: 26px;
            position: relative;
            z-index: 2;
        }

        .logout-modal-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .logout-btn {
            border: none;
            border-radius: 16px;
            padding: 13px 20px;
            min-width: 120px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn-cancel {
            background: #eef2f7;
            color: #475569;
        }

        .logout-btn-cancel:hover {
            background: #e2e8f0;
        }

        .logout-btn-submit {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            color: #ffffff;
            box-shadow: 0 14px 30px rgba(239, 68, 68, 0.24);
        }

        .logout-btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 35px rgba(239, 68, 68, 0.30);
        }

        @media (max-width: 1024px) {
            .kasir-desktop-nav {
                display: none !important;
            }

            .kasir-mobile-nav {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .kasir-page-title {
                font-size: 24px;
            }

            .kasir-stat-value {
                font-size: 30px;
            }
        }

        @media (max-width: 576px) {
            .logout-modal-box {
                padding: 28px 20px 22px;
                border-radius: 22px;
            }

            .logout-modal-title {
                font-size: 24px;
            }

            .logout-modal-actions {
                flex-direction: column-reverse;
            }

            .logout-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

@php
    $userNama = Auth::user()->nama ?? Auth::user()->username ?? 'Kasir';
    $userRole = Auth::user()->role ?? 'Kasir';
    $userInitial = strtoupper(substr($userNama, 0, 1));

    $dashboardUrl = \Illuminate\Support\Facades\Route::has('kasir.dashboard')
        ? route('kasir.dashboard')
        : url('kasir/dashboard');

    $inputPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.create')
        ? route('pesanan.create')
        : url('pesanan/create');

    $dataPesananUrl = \Illuminate\Support\Facades\Route::has('pesanan.index')
        ? route('pesanan.index')
        : url('pesanan');

    $logoutUrl = \Illuminate\Support\Facades\Route::has('logout')
        ? route('logout')
        : url('logout');

    $dashboardActive =
        request()->is('kasir/dashboard') ||
        request()->routeIs('kasir.dashboard');

    $inputActive =
        request()->routeIs('pesanan.create') ||
        request()->is('pesanan/create') ||
        request()->is('kasir/input-pesanan') ||
        request()->is('kasir/order') ||
        request()->is('kasir/order/*');

    $dataPesananActive =
        request()->routeIs('pesanan.index') ||
        request()->routeIs('pesanan.show') ||
        request()->routeIs('pesanan.detail') ||
        request()->routeIs('pesanan.edit') ||
        request()->routeIs('pesanan.bayar') ||
        request()->routeIs('transaksi.*') ||
        request()->routeIs('struk.*') ||
        request()->routeIs('detailpesanan.*') ||
        request()->is('pesanan') ||
        request()->is('pesanan/*/detail') ||
        request()->is('pesanan/*/edit') ||
        request()->is('pesanan/*/bayar') ||
        request()->is('transaksi/*') ||
        request()->is('struk/*');
@endphp

<header class="kasir-topbar sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 md:px-7">
        <div class="h-[68px] flex items-center justify-between gap-4">

            <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 no-underline flex-shrink-0">
                <div class="kasir-brand-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Pande Hill Logo">
</div>
                <div class="hidden sm:block">
                    <div class="kasir-brand-title text-[15px]">Pande Hill</div>
                    <div class="kasir-brand-subtitle">Kasir Panel</div>
                </div>
            </a>

            <nav class="kasir-desktop-nav hidden lg:flex items-center gap-1">
                <a href="{{ $dashboardUrl }}" class="kasir-nav-link {{ $dashboardActive ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ $inputPesananUrl }}" class="kasir-nav-link {{ $inputActive ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Input Pesanan</span>
                </a>

                <a href="{{ $dataPesananUrl }}" class="kasir-nav-link {{ $dataPesananActive ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Data Pesanan</span>
                </a>
            </nav>

            <div class="hidden md:flex items-center gap-2 flex-shrink-0">
                <div class="kasir-user-chip">
                    <div class="kasir-user-avatar">{{ $userInitial }}</div>
                    <div class="min-w-0 pr-2">
                        <div class="text-xs font-bold text-white truncate leading-tight">{{ $userNama }}</div>
                        <div class="text-[10px] truncate capitalize" style="color:rgba(255,255,255,0.4);">
                            {{ $userRole }}
                        </div>
                    </div>
                </div>

                <form action="{{ $logoutUrl }}" method="POST" class="m-0 logout-form">
                    @csrf
                    <button type="button"
                            class="kasir-nav-link"
                            style="color:rgba(255,255,255,0.5);"
                            onclick="openLogoutModal(this)"
                            onmouseover="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'"
                            onmouseout="this.style.background='transparent';this.style.color='rgba(255,255,255,0.5)'">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="hidden xl:inline">Logout</span>
                    </button>
                </form>
            </div>

            <button class="lg:hidden text-white text-xl" onclick="toggleMobileNav()" style="background:none; border:none;">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <div id="mobileNav" class="kasir-mobile-nav lg:hidden pb-4" style="display:none;">
            <div class="flex gap-2 overflow-x-auto pb-2">
                <a href="{{ $dashboardUrl }}" class="kasir-nav-link {{ $dashboardActive ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ $inputPesananUrl }}" class="kasir-nav-link {{ $inputActive ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Input</span>
                </a>

                <a href="{{ $dataPesananUrl }}" class="kasir-nav-link {{ $dataPesananActive ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Data Pesanan</span>
                </a>
            </div>

            <div class="flex items-center justify-between mt-3 gap-3">
                <div class="kasir-user-chip flex-1">
                    <div class="kasir-user-avatar">{{ $userInitial }}</div>
                    <div class="min-w-0 pr-2">
                        <div class="text-xs font-bold text-white truncate">{{ $userNama }}</div>
                        <div class="text-[10px] capitalize" style="color:rgba(255,255,255,0.4);">
                            {{ $userRole }}
                        </div>
                    </div>
                </div>

                <form action="{{ $logoutUrl }}" method="POST" class="m-0 logout-form">
                    @csrf
                    <button type="button"
                            class="kasir-btn kasir-btn-outline !h-[38px]"
                            onclick="openLogoutModal(this)">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 md:px-7 py-7 md:py-9">
    @yield('content')
</main>

<div id="kasirToastContainer" class="kasir-toast-container">
    @if(session('success'))
        <div class="kasir-toast kasir-toast-success" data-toast>
            <div class="kasir-toast-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="kasir-toast-title">Berhasil</div>
                <div class="kasir-toast-message">{{ session('success') }}</div>
            </div>
            <button type="button" class="kasir-toast-close" onclick="dismissKasirToast(this.closest('.kasir-toast'))">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="kasir-toast kasir-toast-error" data-toast>
            <div class="kasir-toast-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <div class="kasir-toast-title">Terjadi Kesalahan</div>
                <div class="kasir-toast-message">{{ session('error') }}</div>
            </div>
            <button type="button" class="kasir-toast-close" onclick="dismissKasirToast(this.closest('.kasir-toast'))">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="kasir-toast kasir-toast-error" data-toast>
            <div class="kasir-toast-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <div class="kasir-toast-title">Validasi Gagal</div>
                <div class="kasir-toast-message">{{ $errors->first() }}</div>
            </div>
            <button type="button" class="kasir-toast-close" onclick="dismissKasirToast(this.closest('.kasir-toast'))">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif
</div>

<div id="logoutConfirmModal" class="logout-modal-overlay">
    <div id="logoutConfirmBox" class="logout-modal-box">
        <div class="logout-modal-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>

        <h3 class="logout-modal-title">Keluar Sistem?</h3>

        <p class="logout-modal-text">
            Yakin ingin logout dari akun ini?
        </p>

        <p class="logout-modal-subtext">
            Kamu bisa login kembali kapan saja menggunakan akun kasir.
        </p>

        <div class="logout-modal-actions">
            <button type="button" class="logout-btn logout-btn-cancel" onclick="closeLogoutModal()">
                Batal
            </button>

            <button type="button" class="logout-btn logout-btn-submit" onclick="submitLogout()">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<script>
    let selectedLogoutForm = null;

    function toggleMobileNav() {
        const nav = document.getElementById('mobileNav');

        if (!nav) {
            return;
        }

        nav.style.display = nav.style.display === 'none' || nav.style.display === '' ? 'block' : 'none';
    }

    function openLogoutModal(button) {
        selectedLogoutForm = button.closest('form');

        const modal = document.getElementById('logoutConfirmModal');

        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutConfirmModal');

        if (modal) {
            modal.classList.remove('show');
        }

        document.body.style.overflow = '';
        selectedLogoutForm = null;
    }

    function submitLogout() {
        if (selectedLogoutForm) {
            selectedLogoutForm.submit();
        }
    }

    function dismissKasirToast(toast) {
        if (!toast || toast.dataset.closing === '1') {
            return;
        }

        toast.dataset.closing = '1';
        toast.classList.add('hide');

        setTimeout(function () {
            toast.remove();
        }, 350);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toasts = document.querySelectorAll('[data-toast]');

        toasts.forEach(function(toast) {
            setTimeout(function() {
                dismissKasirToast(toast);
            }, 4500);
        });

        const logoutModal = document.getElementById('logoutConfirmModal');

        if (logoutModal) {
            logoutModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLogoutModal();
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>

</body>
</html>