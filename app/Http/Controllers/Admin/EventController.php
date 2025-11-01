<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::withCount(['tickets', 'ticketPurchases'])
                      ->latest()
                      ->paginate(10);
        
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'base_price' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|url'
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')
                        ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load(['tickets', 'ticketPurchases.user']);
        
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'base_price' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|url',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled'
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)
                        ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        try {
            // Load related data using joins for comprehensive validation
            $event->load(['tickets', 'ticketPurchases.user']);
            
            // Check if event has sold tickets using join relationships
            $ticketsSold = $event->ticketPurchases()
                ->join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
                ->where('tickets.event_id', $event->id)
                ->count();
                
            if ($ticketsSold > 0) {
                return redirect()->route('admin.events.edit', $event)
                                ->with('error', 'Event kan niet worden verwijderd, er zijn tickets verkocht.');
            }

            $eventName = $event->name;
            $event->delete();

            // Log successful deletion
            Log::info('Event succesvol verwijderd', [
                'event_id' => $event->id,
                'event_name' => $eventName,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('admin.events.index')
                            ->with('success', "Event '{$eventName}' is succesvol verwijderd!");
        } catch (\Exception $e) {
            // Log error
            Log::error('Fout bij verwijderen event', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('admin.events.edit', $event)
                            ->with('error', 'Er is een fout opgetreden bij het verwijderen van het event.');
        }
    }
}
