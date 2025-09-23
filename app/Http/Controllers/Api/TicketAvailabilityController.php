<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class TicketAvailabilityController extends Controller
{
    /**
     * Get real-time ticket availability for an event
     */
    public function getAvailability(Event $event): JsonResponse
    {
        if (!$event->is_active || $event->status !== 'upcoming') {
            return response()->json([
                'success' => false,
                'message' => 'Event not available'
            ], 404);
        }

        $tickets = $event->activeTickets()
                        ->select('id', 'name', 'available_quantity', 'total_quantity')
                        ->get()
                        ->map(function($ticket) {
                            return [
                                'id' => $ticket->id,
                                'name' => $ticket->name,
                                'available_quantity' => $ticket->available_quantity,
                                'total_quantity' => $ticket->total_quantity,
                                'is_available' => $ticket->isAvailable(),
                                'percentage_sold' => $ticket->total_quantity > 0 
                                    ? round((($ticket->total_quantity - $ticket->available_quantity) / $ticket->total_quantity) * 100) 
                                    : 0
                            ];
                        });

        return response()->json([
            'success' => true,
            'event_id' => $event->id,
            'tickets' => $tickets,
            'last_updated' => now()->toISOString()
        ]);
    }
}