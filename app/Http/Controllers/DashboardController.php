<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->role) {
            return redirect('/')->with('error', 'No role assigned. Please contact administrator.');
        }

        // Route users to their specific dashboards based on role
        switch ($user->role->name) {
            case 'organisator':
                // Check if user is also admin (in future we can add admin flag)
                if ($user->email === 'admin@sneakerness.com') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('organisator.dashboard');
                
            case 'verkoper':
                return redirect()->route('verkoper.dashboard');
                
            case 'bezoeker':
                return redirect()->route('bezoeker.dashboard');
                
            default:
                return redirect('/')->with('error', 'Invalid role. Please contact administrator.');
        }
    }
}
