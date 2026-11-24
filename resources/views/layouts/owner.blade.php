{{-- Kerangka dashboard pemilik dengan navigasi ringkasan dan laporan transaksi. --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PARKING POINT - @yield('title', 'Dashboard Owner')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    <style>
        .owner-legacy-content .stat-card{background:linear-gradient(135deg,#0a2540,#1e3a5f);color:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 10px rgba(10,37,64,.08)}
        .owner-legacy-content .stat-card .stat-value{font-size:2rem;font-weight:700;margin:0}.owner-legacy-content .stat-card .stat-label{opacity:.85;font-size:.9rem}
        .owner-legacy-content .card-custom{border:0;border-radius:12px;box-shadow:0 2px 10px rgba(10,37,64,.08)}
        .owner-legacy-content .table-custom thead{background:#0a2540;color:#fff}.owner-legacy-content .table-custom th{font-weight:500}
        @media print{.admin-sidebar,.admin-topbar,.no-print{display:none!important}.admin-main{margin-left:0!important}}
    </style>
</head>
<body class="admin-shell">
    {{-- Memuat aktivitas terbaru yang ditampilkan sebagai notifikasi pemilik. --}}
    @php $notifications = \App\Models\ActivityLog::with('user')->latest()->take(5)->get(); @endphp
    <div class="admin-overlay" data-sidebar-overlay></div>
    <aside class="admin-sidebar" data-sidebar>
        <div class="admin-brand"><span class="admin-brand-mark">P</span><span>PARKING <strong>POINT</strong></span></div>
        <div class="admin-sidebar-label">Workspace</div>
        <nav class="admin-nav" aria-label="Navigasi owner">
            <a href="{{ route('owner.dashboard') }}" class="admin-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"><i data-lucide="layout-dashboard"></i><span>Dashboard</span></a>
            <a href="{{ route('owner.rekap') }}" class="admin-nav-link {{ request()->is('owner/rekap*') ? 'active' : '' }}"><i data-lucide="file-bar-chart-2"></i><span>Rekap Transaksi</span></a>
        </nav>
        <div class="admin-sidebar-footer">
            <div class="admin-user-mini"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>Owner</small></div></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="admin-logout"><i data-lucide="log-out"></i><span>Keluar</span></button></form>
        </div>
    </aside>
    <div class="admin-main">
        {{-- Menyediakan judul halaman, notifikasi, dan area konten dashboard owner. --}}
        <header class="admin-topbar"><div class="admin-topbar-left"><button type="button" class="admin-icon-button admin-menu-button" data-sidebar-toggle aria-label="Buka menu"><i data-lucide="menu"></i></button><div><p class="admin-eyebrow">Overview</p><h1>@yield('page-title', 'Dashboard Owner')</h1></div></div><div class="admin-topbar-actions"><div class="admin-notification-wrap"><button type="button" class="admin-icon-button admin-notification admin-notification-toggle" data-notification-toggle aria-label="Notifikasi"><i data-lucide="bell"></i>@if($notifications->count() > 0)<span class="admin-notification-badge" aria-label="Ada notifikasi"></span>@endif</button><div class="admin-notification-panel" data-notification-panel hidden style="display:none"><div class="admin-notification-header"><strong>Notifikasi</strong></div>@forelse($notifications as $log)<div class="admin-notification-item"><div class="admin-notification-dot"></div><div><strong>{{ $log->aktivitas }}</strong><p>{{ $log->deskripsi ?: ($log->user->name ?? 'Sistem') }}</p><small>{{ $log->created_at?->diffForHumans() ?? '-' }}</small></div></div>@empty<div class="admin-notification-empty">Belum ada notifikasi.</div>@endforelse</div></div><button type="button" class="admin-icon-button" data-theme-toggle aria-label="Ubah tema"><i data-lucide="sun-moon"></i></button><a href="{{ route('owner.dashboard') }}" class="admin-profile-link"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="admin-profile-copy"><strong>{{ auth()->user()->name }}</strong><small>Owner</small></span><i data-lucide="chevron-down"></i></a></div></header>
        <main class="admin-content owner-legacy-content">@if(session('success'))<div class="admin-alert success"><i data-lucide="circle-check"></i><span>{{ session('success') }}</span></div>@endif @if(session('error'))<div class="admin-alert error"><i data-lucide="circle-alert"></i><span>{{ session('error') }}</span></div>@endif @yield('content')</main>
    </div>
    @stack('scripts')
</body>
</html>
