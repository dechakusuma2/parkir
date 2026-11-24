{{-- Menampilkan tiket masuk atau bukti pembayaran yang siap dicetak. --}}
@extends('layouts.petugas')
@section('title', $t->status === 'masuk' ? 'Tiket Parkir' : 'Struk Parkir')
@section('page-title', $t->status === 'masuk' ? 'Tiket Parkir' : 'Struk Parkir')
@section('content')
<div class="no-print mx-auto mb-5 flex max-w-md justify-end gap-2">
    {{-- Tombol navigasi dan cetak disembunyikan saat dokumen dicetak. --}}
    <a href="{{ route('petugas.dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Kembali</a>
    <button onclick="window.print()" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">
        <i data-lucide="printer" class="h-4 w-4"></i>Cetak
    </button>
</div>
<article class="print-area mx-auto max-w-md rounded-xl border border-slate-200 bg-white p-7 text-sm shadow-sm print:max-w-[80mm] print:border-0 print:p-0 print:text-xs print:shadow-none">
    {{-- Rincian tiket berubah sesuai status transaksi masuk atau keluar. --}}
    <div class="text-center">
        <div class="mx-auto mb-2 grid h-10 w-10 place-items-center rounded-xl bg-blue-600 text-white print:hidden">
            <i data-lucide="parking-square"></i>
        </div>
        <h2 class="text-xl font-bold tracking-wide text-blue-700">PARKING POINT</h2>
        <p class="text-xs text-slate-500">{{ $t->status === 'masuk' ? 'Tiket masuk parkir' : 'Bukti pembayaran parkir' }}</p>
    </div>
    <div class="my-5 border-t border-dashed border-slate-300"></div>
    <dl class="space-y-2">
        <div class="flex justify-between"><dt class="text-slate-500">No. Tiket</dt><dd class="font-semibold">{{ $t->kode_transaksi }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Plat nomor</dt><dd class="font-semibold">{{ $t->kendaraan->plat_nomor }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Jenis</dt><dd class="capitalize">{{ $t->kendaraan->jenis }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Warna</dt><dd class="capitalize">{{ $t->kendaraan->warna }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-500">Jam masuk</dt><dd>{{ $t->jam_masuk->format('d/m/Y H:i') }}</dd></div>
        @if($t->status === 'keluar')
            <div class="flex justify-between"><dt class="text-slate-500">Jam keluar</dt><dd>{{ $t->jam_keluar?->format('d/m/Y H:i') ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Durasi</dt><dd>{{ $t->durasi_jam ?? $t->hitungDurasi() }} jam</dd></div>
        @else
            <div class="flex justify-between"><dt class="text-slate-500">Area parkir</dt><dd>{{ $t->area->nama_area }}</dd></div>
        @endif
    </dl>
    <div class="my-5 border-t border-dashed border-slate-300"></div>
    @if($t->status === 'keluar')
        <div class="flex items-center justify-between">
            <span class="font-semibold">Total bayar</span>
            <strong class="text-xl text-blue-700 print:text-base">{{ $t->total_format }}</strong>
        </div>
    @else
        <p class="text-center font-semibold text-amber-700">Simpan tiket ini dan tunjukkan saat kendaraan keluar.</p>
        <p class="mt-2 text-center text-xs text-slate-500">Biaya parkir dibayar saat kendaraan keluar.</p>
    @endif
    <p class="mt-6 text-center text-xs text-slate-400">
        Petugas: {{ $t->petugas->name }}<br>
        Dicetak {{ now()->format('d/m/Y H:i:s') }}<br>
        Terima kasih telah menggunakan layanan kami.
    </p>
</article>
<script>lucide.createIcons();</script>
@endsection
