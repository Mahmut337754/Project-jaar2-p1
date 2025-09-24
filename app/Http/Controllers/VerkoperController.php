<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verkoper;
use Illuminate\Support\Facades\DB;

class VerkoperController extends Controller
{
    // Laat alle actieve verkopers zien
    public function index()
    {
        try {
            $verkopers = DB::table('Verkoper as v1')
                ->join('Verkoper as v2', 'v1.StandType', '=', 'v2.StandType')
                ->where('v1.IsActief', 1)
                ->select('v1.*', 'v2.Naam as AndereVerkoperMetZelfdeStandType')
                ->get();

            // Als er geen actieve verkopers zijn
            if ($verkopers->isEmpty()) {
                return view('verkopers.index', ['message' => 'Op dit moment zijn er geen beschikbare verkopers.']);
            }

            return view('verkopers.index', compact('verkopers'));
        } catch (\Exception $e) {
            \Log::error('Fout bij ophalen verkopers: ' . $e->getMessage());
            return back()->with('error', 'Er is iets misgegaan bij het ophalen van de verkopers.');
        }
    }

    // Wis alle verkopers (zet IsActief = 0)
    public function wisAlles()
    {
        try {
            Verkoper::query()->update(['IsActief' => 0]);
            return redirect()->route('verkopers.index')->with('success', 'Alle verkopers zijn tijdelijk verwijderd.');
        } catch (\Exception $e) {
            \Log::error('Fout bij wissen verkopers: ' . $e->getMessage());
            return back()->with('error', 'Er is iets misgegaan bij het wissen van de verkopers.');
        }
    }

    // Herstel alle verkopers (zet IsActief = 1)
    public function herstelAlles()
    {
        try {
            Verkoper::query()->update(['IsActief' => 1]);
            return redirect()->route('verkopers.index')->with('success', 'Alle verkopers zijn weer actief.');
        } catch (\Exception $e) {
            \Log::error('Fout bij herstellen verkopers: ' . $e->getMessage());
            return back()->with('error', 'Er is iets misgegaan bij het herstellen van de verkopers.');
        }
    }
}
