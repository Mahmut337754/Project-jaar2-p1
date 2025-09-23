<?php

namespace App\Http\Controllers\Organisator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganisatorDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => 0, // Will implement when Event model exists
            'total_tickets_sold' => 0, // Will implement when Ticket model exists
            'total_stands_rented' => 0, // Will implement when Stand model exists
            'total_revenue' => 0, // Will implement later
        ];

        return view('organisator.dashboard', compact('stats'));
    }

    public function events()
    {
        // Will implement when Event model exists
        return view('organisator.events.index');
    }

    public function tickets()
    {
        // Will implement when Ticket model exists
        return view('organisator.tickets.index');
    }

    public function stands()
    {
        // Will implement when Stand model exists
        return view('organisator.stands.index');
    }
}
