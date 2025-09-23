<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_organisators' => User::whereHas('role', function($q) {
                $q->where('name', 'organisator');
            })->count(),
            'total_verkopers' => User::whereHas('role', function($q) {
                $q->where('name', 'verkoper');
            })->count(),
            'total_bezoekers' => User::whereHas('role', function($q) {
                $q->where('name', 'bezoeker');
            })->count(),
        ];

        $recent_users = User::with('role')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }

    public function users()
    {
        $users = User::with('role')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
}
