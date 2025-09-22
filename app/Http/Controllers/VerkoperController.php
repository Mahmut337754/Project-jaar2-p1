<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verkoper;

class VerkoperController extends Controller
{
    public function index()
    {
        // Haal alle actieve verkopers op
        $verkopers = Verkoper::where('IsActief', 1)->get();

        return view('verkopers.index', compact('verkopers'));
    }
}
