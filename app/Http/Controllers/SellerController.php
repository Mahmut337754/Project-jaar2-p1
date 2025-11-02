<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    // Toon alle verkopers
    public function index()
    {
        $sellers = Seller::latest()->get();
        return view('sellers.index', compact('sellers'));
    }

    // Formulier voor nieuwe verkoper
    public function create()
    {
        return view('sellers.create');
    }

    // Verwerker voor het opslaan van een nieuwe verkoper
    public function store(Request $request)
    {
        $validated = $this->validateSeller($request);

        try {
            if ($request->hasFile('logo')) {
                // Upload logo naar opslag
                $validated['logo'] = $request->file('logo')->store('sellers/logos', 'public');
            }

            Seller::create($validated);

            return redirect()->route('sellers.index')
                ->with('success', 'Verkoper succesvol aangemaakt.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fout bij aanmaken verkoper: ' . $e->getMessage());
        }
    }

    // Formulier voor bewerken van verkoper
    public function edit(Seller $seller)
    {
        return view('sellers.edit', compact('seller'));
    }

    // Verwerker voor bijwerken van verkoper
    public function update(Request $request, Seller $seller)
    {
        $validated = $this->validateSeller($request, $seller->id);

        try {
            if ($request->hasFile('logo')) {
                // Verwijder oud logo en upload nieuw logo
                if ($seller->logo) {
                    Storage::disk('public')->delete($seller->logo);
                }
                $validated['logo'] = $request->file('logo')->store('sellers/logos', 'public');
            }

            $seller->update($validated);

            return redirect()->route('sellers.index')
                ->with('success', 'Verkoper succesvol bijgewerkt.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fout bij bijwerken verkoper: ' . $e->getMessage());
        }
    }

    // Verwijder een verkoper
    public function destroy(Seller $seller)
    {
        try {
            // Actieve verkopers kunnen niet verwijderd worden
            if ($seller->isActive()) {
                return redirect()->route('sellers.index')
                    ->with('error', 'Je kan geen actieve verkoper verwijderen.');
            }

            // Verwijder logo als deze bestaat
            if ($seller->logo) {
                Storage::disk('public')->delete($seller->logo);
            }

            $seller->delete();

            return redirect()->route('sellers.index')
                ->with('success', 'Verkoper is succesvol verwijderd.');
                
        } catch (\Exception $e) {
            return redirect()->route('sellers.index')
                ->with('error', 'Fout bij verwijderen verkoper: ' . $e->getMessage());
        }
    }

    // Validatie voor verkoper formulier
    private function validateSeller(Request $request, $sellerId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'special_status' => 'boolean',
            'selling_type' => 'required|string|in:' . implode(',', array_keys(Seller::SELLING_TYPES)),
            'booth_type' => 'required|string|in:' . implode(',', array_keys(Seller::BOOTH_TYPES)),
            'days' => 'required|integer|in:1,2',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];

        return $request->validate($rules);
    }
}
