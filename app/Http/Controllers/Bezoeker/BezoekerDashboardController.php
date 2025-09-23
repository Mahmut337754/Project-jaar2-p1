<?php

namespace App\Http\Controllers\Bezoeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BezoekerDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'my_tickets' => 0, // Will implement when Ticket model exists
            'upcoming_events' => 0, // Will implement when Event model exists
            'total_spent' => 0, // Will implement later
        ];

        return view('bezoeker.dashboard', compact('stats'));
    }

    public function tickets()
    {
        // Will implement when Ticket model exists
        return view('bezoeker.tickets.index');
    }
}
