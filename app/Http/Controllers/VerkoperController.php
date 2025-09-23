<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verkoper;

class VerkoperController extends Controller
{
    // Laat alle actieve verkopers zien
    public function index()
    {
        // Selecteer verkopers die actief zijn (IsActief = 1)
        $verkopers = Verkoper::where('IsActief', 1)->get();

        // Stuur de verkopers door naar de view
        return view('verkopers.index', compact('verkopers'));
    }
}
