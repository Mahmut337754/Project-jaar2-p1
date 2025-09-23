<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tickets = Ticket::with(['event', 'purchases'])
                        ->latest()
                        ->paginate(15);
        
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $event = null;
        if ($request->has('event')) {
            $event = Event::findOrFail($request->event);
        }
        
        $events = Event::where('status', '!=', 'cancelled')->get();
        
        return view('admin.tickets.create', compact('events', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'day' => 'required|in:saturday,sunday,both',
            'admission_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'features' => 'nullable|array'
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.events.show', $validated['event_id'])
                        ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): View
    {
        $ticket->load(['event', 'purchases.user']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket): View
    {
        $events = Event::where('status', '!=', 'cancelled')->get();
        
        return view('admin.tickets.edit', compact('ticket', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'day' => 'required|in:saturday,sunday,both',
            'admission_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'features' => 'nullable|array'
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.events.show', $ticket->event_id)
                        ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $eventId = $ticket->event_id;
        
        // Check if ticket has purchases
        if ($ticket->purchases()->count() > 0) {
            return redirect()->route('admin.events.show', $eventId)
                            ->with('error', 'Cannot delete ticket with existing purchases!');
        }
        
        $ticket->delete();

        return redirect()->route('admin.events.show', $eventId)
                        ->with('success', 'Ticket deleted successfully!');
    }
}
