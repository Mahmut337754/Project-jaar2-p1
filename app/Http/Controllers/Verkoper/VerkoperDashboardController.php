<?php

namespace App\Http\Controllers\Verkoper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerkoperDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'my_stands' => 0, // Will implement when Stand model exists
            'total_bookings' => 0, // Will implement when Booking model exists
            'upcoming_events' => 0, // Will implement when Event model exists
            'total_spent' => 0, // Will implement later
        ];

        return view('verkoper.dashboard', compact('stats'));
    }

    public function stands()
    {
        // Will implement when Stand model exists
        return view('verkoper.stands.index');
    }

    public function bookings()
    {
        // Will implement when Booking model exists
        return view('verkoper.bookings.index');
    }
}
