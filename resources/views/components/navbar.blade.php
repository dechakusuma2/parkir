<div class="top-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.querySelector('.sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="page-title mb-0">@yield('page-title', 'Dashboard')</h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">
            <i class="bi bi-calendar3"></i>
            {{ now()->format('d M Y') }}
        </span>
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
