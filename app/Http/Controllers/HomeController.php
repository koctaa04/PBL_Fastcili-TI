<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.dashboard');
    }


    public function pelapor()
    {
        $laporan = LaporanKerusakan::where('id_user', Auth::id())->get();
        $status = LaporanKerusakan::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        return view('users.pelapor.index', compact('laporan', 'status'));
    }
}
