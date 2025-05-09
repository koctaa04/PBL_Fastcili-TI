<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
            public function index()
    {
        $gedung = Gedung::all();
        return view('gedung.index', ['gedung' => $gedung]);
    }
}
