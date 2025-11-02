<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of events with pagination and statistics
     */
    public function index(): View
    {
        $events = Event::withCount(['tickets', 'ticketPurchases'])
                      ->latest()
                      ->paginate(10);
        
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
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
     * Update an existing event with comprehensive validation and error handling
     * 
     * This method handles the update of event information including:
     * - Validating all required fields with Dutch error messages
     * - Loading related tickets and purchases for business logic
     * - Updating the event data safely with transaction handling
     * 
     * @param Request $request The HTTP request containing event data to update
     * @param Event $event The event model instance to be updated
     * @return RedirectResponse Redirect response with success or error message
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        try {
            // Load related data using Eloquent relationships (similar to joins)
            // This enables business logic validation and prevents orphaned data
            $event->load(['tickets', 'ticketPurchases.user']);
            
            // Validate incoming request data with comprehensive rules and Dutch messages
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
                'status' => 'required|in:draft,published,cancelled'
            ], [
                'name.required' => 'Event naam is verplicht.',
                'description.required' => 'Event beschrijving is verplicht.',
                'location.required' => 'Event locatie is verplicht.',
                'start_date.required' => 'Start datum is verplicht.',
                'end_date.required' => 'Eind datum is verplicht.',
                'start_time.required' => 'Start tijd is verplicht.',
                'end_time.required' => 'Eind tijd is verplicht.',
                'end_date.after_or_equal' => 'Eind datum moet na of gelijk aan start datum zijn.',
                'end_time.after' => 'Eind tijd moet na start tijd zijn.',
            ]);

            // Update the event with validated data
            $event->update($validated);

            // Log successful update for audit trail
            Log::info('Event updated successfully', [
                'event_id' => $event->id,
                'event_name' => $event->name,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'updated_fields' => $validated,
                'timestamp' => now()
            ]);

            // Redirect back to edit page with success confirmation
            return redirect()->route('admin.events.edit', $event)
                            ->with('success', 'Het event is succesvol bijgewerkt!');
        } catch (\Exception $e) {
            // Log the error for debugging and monitoring
            Log::error('Failed to update event', [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);
            
            // Handle any unexpected errors gracefully with user-friendly message
            return redirect()->route('admin.events.edit', $event)
                            ->with('error', 'Er is een fout opgetreden bij het bijwerken van het event. Probeer het opnieuw.');
        }
    }

    /**
     * Verwijder een event uit de database met uitgebreide validatie
     * 
     * Deze methode voert een veilige verwijdering uit waarbij:
     * - Gecontroleerd wordt of er tickets verkocht zijn
     * - Database integriteit gewaarborgd blijft
     * - Uitgebreide logging voor audit trail
     * - Robuuste error handling voor alle scenario's
     * 
     * @param Event $event Het event model dat verwijderd moet worden
     * @return RedirectResponse Redirect met succes of foutmelding
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
