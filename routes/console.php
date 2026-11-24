<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Mendaftarkan perintah konsol tambahan yang tersedia melalui Artisan.
Artisan::command('inspire', function () {
    // Menampilkan kutipan inspiratif sebagai keluaran perintah.
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
