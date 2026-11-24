{{-- Menampilkan riwayat aktivitas pengguna beserta waktu, deskripsi, dan alamat IP. --}}
@extends('layouts.admin')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')
@section('content')
<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-journal-text"></i> Riwayat Aktivitas Sistem
    </h5>

    <div class="table-responsive">
        {{-- Daftar log disajikan dalam tabel dengan keadaan kosong bila belum ada data. --}}
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aktivitas</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                <tr>
                    <td>{{ $logs->firstItem() + $i }}</td>
                    <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                    <td>
                        <strong>{{ $log->user->name ?? 'System' }}</strong>
                        <br><small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                    </td>
                    <td>{!! $log->aktivitasBadge !!}</td>
                    <td>{{ $log->deskripsi ?? '-' }}</td>
                    <td><code>{{ $log->ip_address }}</code></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada log aktivitas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="mt-3">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
