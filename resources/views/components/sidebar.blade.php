{{-- Menampilkan identitas pengguna dan navigasi yang disesuaikan dengan perannya. --}}
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-p-circle-fill"></i>
        <span>PARKING POINT</span>
    </div>

    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="fw-semibold">{{ auth()->user()->name }}</div>
        <small class="opacity-75">
            @if(auth()->user()->isOwner())
                <i class="bi bi-crown-fill text-warning"></i> Owner
            @elseif(auth()->user()->isAdmin())
                <i class="bi bi-shield-fill-check text-info"></i> Admin
            @else
                <i class="bi bi-person-badge-fill text-success"></i> Petugas
            @endif
        </small>
    </div>

    <ul class="sidebar-menu">
        {{-- Menu pemilik hanya muncul saat akun aktif memiliki peran owner. --}}
        @if(auth()->user()->isOwner())
            <li class="menu-label">Owner Menu</li>
            <li><a href="{{ route('owner.dashboard', [], false) ? '/owner/dashboard' : '/owner/dashboard' }}" class="{{ request()->is('owner/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><a href="/owner/rekap" class="{{ request()->is('owner/rekap*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Rekap Transaksi
            </a></li>
        @endif

        {{-- Menu administrasi ditampilkan khusus untuk akun admin. --}}
        @if(auth()->user()->isAdmin())
            <li class="menu-label">Admin Menu</li>
            <li><a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> User Management
            </a></li>
            <li><a href="/admin/tarifs" class="{{ request()->is('admin/tarifs*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Tarif Parkir
            </a></li>
            <li><a href="/admin/areas" class="{{ request()->is('admin/areas*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i> Area Parkir
            </a></li>
            <li><a href="/admin/kendaraans" class="{{ request()->is('admin/kendaraans*') ? 'active' : '' }}">
                <i class="bi bi-car-front-fill"></i> Kendaraan
            </a></li>
            <li><a href="/admin/logs" class="{{ request()->is('admin/logs*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Log Aktivitas
            </a></li>
        @endif

        {{-- Menu operasional ditampilkan khusus untuk akun petugas. --}}
        @if(auth()->user()->isPetugas())
            <li class="menu-label">Petugas Menu</li>
            <li><a href="/petugas/dashboard" class="{{ request()->is('petugas/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><a href="{{ route('petugas.transaksi') }}" class="{{ request()->is('petugas/transaksi*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transaksi
            </a></li>
        @endif

        <li class="menu-label mt-3">Account</li>
        <li>
            <form action="/logout" method="POST" class="d-inline w-100">
                @csrf
                <button type="submit" class="btn btn-link text-white w-100 text-start" style="padding:12px 20px;border:none;display:flex;align-items:center;gap:12px;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
