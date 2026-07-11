<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Halaman Tentang LaporHijau.
     */
    public function tentang(): View
    {
        return view('tentang');
    }

    /**
     * Halaman panduan cara melapor.
     */
    public function caraLapor(): View
    {
        return view('cara-lapor');
    }
}
