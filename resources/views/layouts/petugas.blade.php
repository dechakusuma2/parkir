{{-- Kerangka responsif panel petugas dengan navigasi, notifikasi, dan jam operasional. --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PARKING POINT | @yield('title', 'Dashboard Petugas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { background: #f8fafc; }
        #notification-toggle > span { background: #22c55e !important; border: 2px solid #fff; border-radius: 50%; display: block; height: 9px !important; min-width: 9px !important; padding: 0 !important; position: absolute; right: 2px; top: 2px; width: 9px; }
        #notification-panel[hidden] { display: none !important; }
        #notification-panel:not([hidden]) { display: block !important; }
        #notification-panel .notification-count { display: none; }
        .sidebar { transition: transform .25s ease, width .25s ease; }
        .sidebar-collapsed { width: 84px; }
        .sidebar-collapsed .nav-label, .sidebar-collapsed .brand-label { display: none; }
        .sidebar-collapsed .nav-link { justify-content: center; }
        .main-shell { transition: margin-left .25s ease; }
        @media (max-width: 767px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-shell { margin-left: 0 !important; }
            .sidebar-collapsed { width: 280px; }
            .sidebar-collapsed .nav-label, .sidebar-collapsed .brand-label { display: block; }
            .sidebar-collapsed .nav-link { justify-content: flex-start; }
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .print-area { display: block !important; }
        }
    </style>
</head>
<body class="font-sans text-slate-800">
    {{-- Memuat aktivitas terbaru untuk panel notifikasi petugas. --}}
    @php $notifications = \App\Models\ActivityLog::with('user')->latest()->take(5)->get(); @endphp
    <div id="mobile-backdrop" class="fixed inset-0 z-30 hidden bg-slate-950/40 md:hidden"></div>

    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-40 flex w-[280px] flex-col border-r border-slate-200 bg-white text-slate-600 shadow-sm md:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-slate-200 px-6">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-600 text-white"><i data-lucide="parking-square"></i></div>
            <div class="brand-label"><p class="text-sm font-semibold tracking-wide text-slate-900">PARKING POINT</p><p class="text-xs text-slate-400">Panel Petugas</p></div>
        </div>
        <nav class="flex-1 space-y-1 px-3 py-6">
            <p class="nav-label px-3 pb-3 text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Menu utama</p>
            <a href="{{ route('petugas.dashboard') }}" class="nav-link flex items-center gap-3 rounded-lg border-l-2 border-blue-600 bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-700"><i data-lucide="layout-dashboard" class="h-4 w-4"></i><span class="nav-label">Dashboard</span></a>
            <a href="{{ route('petugas.dashboard', ['tab' => 'masuk']) }}" class="nav-link flex items-center gap-3 rounded-lg border-l-2 border-transparent px-3 py-3 text-sm hover:bg-slate-50 hover:text-blue-700"><i data-lucide="log-in" class="h-4 w-4"></i><span class="nav-label">Kendaraan Masuk</span></a>
            <a href="{{ route('petugas.dashboard', ['tab' => 'keluar']) }}" class="nav-link flex items-center gap-3 rounded-lg border-l-2 border-transparent px-3 py-3 text-sm hover:bg-slate-50 hover:text-blue-700"><i data-lucide="log-out" class="h-4 w-4"></i><span class="nav-label">Kendaraan Keluar</span></a>
            <a href="{{ route('petugas.transaksi') }}" class="nav-link flex items-center gap-3 rounded-lg border-l-2 border-transparent px-3 py-3 text-sm hover:bg-slate-50 hover:text-blue-700"><i data-lucide="history" class="h-4 w-4"></i><span class="nav-label">Riwayat Transaksi</span></a>
        </nav>
        <div class="border-t border-slate-200 p-3">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button class="nav-link flex w-full items-center gap-3 rounded-lg border-l-2 border-transparent px-3 py-3 text-sm text-slate-500 hover:bg-red-50 hover:text-red-600"><i data-lucide="log-out" class="h-4 w-4"></i><span class="nav-label">Keluar</span></button>
            </form>
        </div>
    </aside>

    <div id="main-shell" class="main-shell ml-[280px] min-h-screen">
        <header class="no-print sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/90 px-5 backdrop-blur md:px-8">
            <div class="flex items-center gap-4"><button id="sidebar-toggle" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" title="Buka/tutup sidebar"><i data-lucide="menu"></i></button><div><p class="text-xs font-medium text-slate-400">Ruang kerja petugas</p><h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Dashboard Petugas')</h1></div></div>
            <div class="flex items-center gap-3"><span id="clock" class="hidden text-sm font-medium text-slate-500 sm:inline"></span><div class="relative"><button id="notification-toggle" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100" title="Notifikasi" type="button"><i data-lucide="bell" class="h-5 w-5"></i>@if($notifications->count() > 0)<span aria-label="Ada notifikasi"></span>@endif</button><div id="notification-panel" hidden style="display:none" class="absolute right-0 top-12 z-40 w-80 rounded-xl border border-slate-200 bg-white p-2 shadow-xl"><div class="mb-2 flex items-center justify-between px-2 py-1"><strong class="text-sm text-slate-800">Notifikasi</strong><span class="notification-count">{{ $notifications->count() }} baru</span></div>@forelse($notifications as $log)<div class="flex gap-2 rounded-lg px-2 py-2 hover:bg-slate-50"><div class="mt-1 h-2.5 w-2.5 rounded-full bg-blue-500"></div><div class="min-w-0 flex-1"><p class="text-sm font-medium text-slate-800">{{ $log->aktivitas }}</p><p class="text-xs text-slate-500">{{ $log->deskripsi ?: ($log->user->name ?? 'Sistem') }}</p><small class="text-[10px] text-slate-400">{{ $log->created_at?->diffForHumans() ?? '-' }}</small></div></div>@empty<div class="px-2 py-3 text-sm text-slate-500">Belum ada notifikasi.</div>@endforelse</div></div><div class="flex items-center gap-2 border-l border-slate-200 pl-3"><span class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 font-semibold text-blue-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="hidden text-sm font-medium sm:inline">{{ auth()->user()->name }}</span></div></div>
        </header>
        <main class="p-5 md:p-8">
            @if(session('success'))<div class="no-print mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="no-print mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>@endif
            @yield('content')
        </main>
    </div>

    <script>
        // Mengaktifkan ikon dan mengatur navigasi responsif serta interaksi panel.
        window.lucide?.createIcons?.();
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-shell');
        const backdrop = document.getElementById('mobile-backdrop');
        document.getElementById('sidebar-toggle').onclick = () => {
            if (innerWidth < 768) {
                sidebar.classList.toggle('mobile-open');
                backdrop.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('sidebar-collapsed');
                main.classList.toggle('ml-[84px]');
                main.classList.toggle('ml-[280px]');
            }
        };
        backdrop.onclick = () => { sidebar.classList.remove('mobile-open'); backdrop.classList.add('hidden'); };
        const notificationToggle = document.getElementById('notification-toggle');
        const notificationPanel = document.getElementById('notification-panel');
        notificationToggle?.addEventListener('click', () => {
            if (!notificationPanel) return;
            notificationPanel.hidden = !notificationPanel.hidden;
            notificationPanel.style.display = notificationPanel.hidden ? 'none' : 'block';
        });
        document.addEventListener('click', (event) => {
            if (!notificationToggle?.contains(event.target) && !notificationPanel?.contains(event.target)) {
                if (notificationPanel) {
                    notificationPanel.hidden = true;
                    notificationPanel.style.display = 'none';
                }
            }
        });
        // Memperbarui jam lokal yang ditampilkan pada bilah atas.
        function tick() { document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); }
        tick();
        setInterval(tick, 1000);
    </script>
</body>
</html>
