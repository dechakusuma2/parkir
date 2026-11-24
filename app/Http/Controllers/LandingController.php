<?php

namespace App\Http\Controllers;

/** Menyediakan halaman publik utama aplikasi. */
class LandingController extends Controller
{
    /** Menampilkan halaman utama aplikasi parkir. */
    public function index()
    {
        return view('landing');
    }
}
