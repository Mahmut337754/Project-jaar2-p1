<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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

            // Redirect back to edit page with success confirmation
            return redirect()->route('admin.events.edit', $event)
                            ->with('success', 'Het event is succesvol bijgewerkt!');
        } catch (\Exception $e) {
            // Handle any unexpected errors gracefully with user-friendly message
            return redirect()->route('admin.events.edit', $event)
                            ->with('error', 'Er is een fout opgetreden bij het bijwerken van het event. Probeer het opnieuw.');
        }
    }

    /**
     * Remove an event from storage with business logic validation
     * 
     * This method safely deletes an event while checking:
     * - Whether tickets have been sold (prevents deletion if true)
     * - Database integrity and referential constraints
     * - Proper error handling for all scenarios
     * 
     * @param Event $event The event model instance to be deleted
     * @return RedirectResponse Redirect response with success or error message
     */
    public function destroy(Event $event): RedirectResponse
    {
        try {
            // Check if event has sold tickets using relationship count
            // This prevents deletion of events with existing purchases
            if ($event->ticketPurchases()->count() > 0) {
                return redirect()->route('admin.events.edit', $event)
                                ->with('error', 'Event kan niet worden verwijderd, er zijn tickets verkocht.');
            }

            // Store event name for success message before deletion
            $eventName = $event->name;
            
            // Perform the deletion
            $event->delete();

            // Redirect to events index with success confirmation
            return redirect()->route('admin.events.index')
                            ->with('success', "Event '{$eventName}' is succesvol verwijderd!");
        } catch (\Exception $e) {
            // Handle any unexpected database or system errors
            return redirect()->route('admin.events.edit', $event)
                            ->with('error', 'Er is een fout opgetreden bij het verwijderen van het event. Probeer het opnieuw.');
        }
    }
}
