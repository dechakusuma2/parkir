{{-- Kerangka dashboard admin yang memuat navigasi, notifikasi, konten, dan dialog keluar. --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PARKING POINT - @yield('title', 'Dashboard Admin')</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    {{-- Mengambil lima aktivitas terbaru untuk panel notifikasi admin. --}}
    @php $notifications = \App\Models\ActivityLog::with('user')->latest()->take(5)->get(); @endphp
    <div class="admin-overlay" data-sidebar-overlay></div>
    <aside class="admin-sidebar" data-sidebar>
        <div class="admin-brand">
            <span class="admin-brand-mark">P</span>
            <span>PARKING <strong>POINT</strong></span>
        </div>
        <div class="admin-sidebar-label">Workspace</div>
        <nav class="admin-nav" aria-label="Navigasi admin">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                <i data-lucide="layout-dashboard"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" title="User Management">
                <i data-lucide="users-round"></i><span>User Management</span>
            </a>
            <a href="{{ url('/admin/tarif') }}" class="admin-nav-link {{ request()->is('admin/tarif*', 'admin/tarifs*') ? 'active' : '' }}" title="Tarif Parkir">
                <i data-lucide="wallet-cards"></i><span>Tarif Parkir</span>
            </a>
            <a href="{{ url('/admin/area') }}" class="admin-nav-link {{ request()->is('admin/area*', 'admin/areas*') ? 'active' : '' }}" title="Area Parkir">
                <i data-lucide="map-pinned"></i><span>Area Parkir</span>
            </a>
            <a href="{{ url('/admin/kendaraan') }}" class="admin-nav-link {{ request()->is('admin/kendaraan*') ? 'active' : '' }}" title="Kendaraan">
                <i data-lucide="car-front"></i><span>Kendaraan</span>
            </a>
            <a href="{{ url('/admin/log-aktivitas') }}" class="admin-nav-link {{ request()->is('admin/log*') }}" title="Log Aktivitas">
                <i data-lucide="scroll-text"></i><span>Log Aktivitas</span>
            </a>
        </nav>
        <div class="admin-sidebar-footer">
            <a href="{{ url('/admin/pengaturan-akun') }}" class="admin-nav-link {{ request()->is('admin/pengaturan-akun') ? 'active' : '' }}" title="Pengaturan Akun">
                <i data-lucide="settings-2"></i><span>Pengaturan Akun</span>
            </a>
            <div class="admin-user-mini">
                <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div><strong>{{ auth()->user()->name }}</strong><small>Administrator</small></div>
            </div>
            <form method="POST" action="{{ route('logout') }}" data-logout-form>
                @csrf
                <button type="submit" class="admin-logout"><i data-lucide="log-out"></i><span>Keluar</span></button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        {{-- Bilah atas berisi judul, kontrol antarmuka, notifikasi, dan profil admin. --}}
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <button type="button" class="admin-icon-button admin-menu-button" data-sidebar-toggle aria-label="Buka menu"><i data-lucide="menu"></i></button>
                <div><p class="admin-eyebrow">Overview</p><h1>@yield('page-title', 'Dashboard Admin')</h1></div>
            </div>
            <div class="admin-topbar-actions">
                <label class="admin-search"><i data-lucide="search"></i><input type="search" placeholder="Cari sesuatu..." aria-label="Cari"></label>
                <button type="button" class="admin-icon-button" data-theme-toggle aria-label="Ubah tema"><i data-lucide="sun-moon"></i></button>
                <div class="admin-notification-wrap">
                    <button type="button" class="admin-icon-button admin-notification admin-notification-toggle" data-notification-toggle aria-label="Notifikasi">
                        <i data-lucide="bell"></i>
                        @if($notifications->count() > 0)
                            <span class="admin-notification-badge" aria-label="Ada notifikasi"></span>
                        @endif
                    </button>
                    <div class="admin-notification-panel" data-notification-panel hidden style="display:none">
                        <div class="admin-notification-header">
                            <strong>Notifikasi</strong>
                        </div>
                        @forelse($notifications as $log)
                            <div class="admin-notification-item">
                                <div class="admin-notification-dot"></div>
                                <div>
                                    <strong>{{ $log->aktivitas }}</strong>
                                    <p>{{ $log->deskripsi ?: ($log->user->name ?? 'Sistem') }}</p>
                                    <small>{{ $log->created_at?->diffForHumans() ?? '-' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="admin-notification-empty">Belum ada notifikasi.</div>
                        @endforelse
                    </div>
                </div>
                <a href="{{ url('/admin/pengaturan-akun') }}" class="admin-profile-link"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="admin-profile-copy"><strong>{{ auth()->user()->name }}</strong><small>Admin</small></span><i data-lucide="chevron-down"></i></a>
            </div>
        </header>
        <main class="admin-content">
            {{-- Menampilkan umpan balik sesi atau validasi sebelum konten halaman. --}}
            @if(session('success')) <div class="admin-alert success"><i data-lucide="circle-check"></i><span>{{ session('success') }}</span></div> @endif
            @if(session('error')) <div class="admin-alert error"><i data-lucide="circle-alert"></i><span>{{ session('error') }}</span></div> @endif
            @if($errors->any()) <div class="admin-alert error"><i data-lucide="circle-alert"></i><span>{{ $errors->first() }}</span></div> @endif
            @yield('content')
        </main>
    </div>

    <div class="admin-modal" data-logout-modal aria-hidden="true">
        <div class="admin-modal-card" role="dialog" aria-modal="true" aria-labelledby="logout-title">
            <div class="admin-modal-icon"><i data-lucide="log-out"></i></div>
            <h2 id="logout-title">Yakin ingin keluar?</h2><p>Sesi admin akan diakhiri dan Anda kembali ke halaman utama.</p>
            <div class="admin-modal-actions"><button type="button" class="admin-button secondary" data-modal-close>Batal</button><button type="button" class="admin-button danger" data-logout-confirm>Ya, keluar</button></div>
        </div>
    </div>
</body>
</html>
