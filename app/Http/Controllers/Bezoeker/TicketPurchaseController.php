<?php

namespace App\Http\Controllers\Bezoeker;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchaseConfirmation;

class TicketPurchaseController extends Controller
{
    /**
     * Show available events for ticket purchase
     */
    public function index(Request $request): View
    {
        $query = Event::with(['activeTickets'])
                     ->where('is_active', true)
                     ->where('status', 'upcoming')
                     ->where('start_date', '>', now());

        // Search by name
        if ($search = $request->get('search')) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        // Filter by location
        if ($location = $request->get('location')) {
            $query->where('location', 'LIKE', '%' . $location . '%');
        }

        // Filter by date range
        if ($dateFilter = $request->get('date')) {
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('start_date', now()->toDateString());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_date', now()->addDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'next_month':
                    $query->whereBetween('start_date', [now()->addMonth()->startOfMonth(), now()->addMonth()->endOfMonth()]);
                    break;
            }
        }

        $events = $query->orderBy('start_date')->get();

        // Filter by price range (after loading events with tickets)
        if ($priceFilter = $request->get('price')) {
            $events = $events->filter(function($event) use ($priceFilter) {
                $minPrice = $event->activeTickets->min('price');
                if (!$minPrice) return false;

                switch ($priceFilter) {
                    case '0-25':
                        return $minPrice >= 0 && $minPrice <= 25;
                    case '25-50':
                        return $minPrice > 25 && $minPrice <= 50;
                    case '50-100':
                        return $minPrice > 50 && $minPrice <= 100;
                    case '100+':
                        return $minPrice > 100;
                    default:
                        return true;
                }
            });
        }

        return view('bezoeker.tickets.index', compact('events'));
    }

    /**
     * Show tickets available for a specific event
     */
    public function show(Event $event): View
    {
        if (!$event->is_active || $event->status !== 'upcoming') {
            abort(404, 'Event not available for ticket purchase');
        }

        $saturdayTickets = $event->activeTickets()
                                 ->where('day', 'saturday')
                                 ->orderBy('admission_time')
                                 ->get();

        $sundayTickets = $event->activeTickets()
                               ->where('day', 'sunday')
                               ->orderBy('admission_time')
                               ->get();

        return view('bezoeker.tickets.show', compact('event', 'saturdayTickets', 'sundayTickets'));
    }

    /**
     * Show purchase form for a specific ticket
     */
    public function purchase(Event $event, Ticket $ticket): View
    {
        if (!$ticket->isAvailable() || $ticket->event_id !== $event->id) {
            abort(404, 'Ticket not available');
        }

        return view('bezoeker.tickets.purchase', compact('event', 'ticket'));
    }

    /**
     * Process ticket purchase
     */
    public function store(Request $request, Event $event, Ticket $ticket): RedirectResponse
    {
        try {
            // Check ticket availability
            if (!$ticket->isAvailable() || $ticket->event_id !== $event->id) {
                Log::warning('Attempted to purchase unavailable ticket', [
                    'user_id' => Auth::id(),
                    'ticket_id' => $ticket->id,
                    'event_id' => $event->id,
                    'ticket_available' => $ticket->isAvailable(),
                    'ticket_belongs_to_event' => $ticket->event_id === $event->id
                ]);
                
                return redirect()->back()->with('error', 'Ticket is no longer available');
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:10',
                'buyer_name' => 'required|string|max:255',
                'buyer_email' => 'required|email|max:255',
                'buyer_phone' => 'nullable|string|max:20',
            ]);

            $quantity = $validated['quantity'];

            // Check if enough tickets are available
            if ($ticket->available_quantity < $quantity) {
                Log::warning('Insufficient ticket quantity attempted', [
                    'user_id' => Auth::id(),
                    'ticket_id' => $ticket->id,
                    'requested_quantity' => $quantity,
                    'available_quantity' => $ticket->available_quantity
                ]);
                
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Not enough tickets available. Only ' . $ticket->available_quantity . ' tickets left.');
            }

            // Process purchase in database transaction
            $purchase = DB::transaction(function () use ($validated, $event, $ticket, $quantity) {
                // Calculate total price
                $unitPrice = $ticket->price;
                $totalPrice = $unitPrice * $quantity;

                // Create purchase record
                $purchase = TicketPurchase::create([
                    'user_id' => Auth::id(),
                    'ticket_id' => $ticket->id,
                    'event_id' => $event->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'purchase_reference' => TicketPurchase::generateReference(),
                    'status' => 'confirmed',
                    'buyer_name' => $validated['buyer_name'],
                    'buyer_email' => $validated['buyer_email'],
                    'buyer_phone' => $validated['buyer_phone'] ?? null,
                    'purchased_at' => now(),
                ]);

                // Update ticket sold quantity
                $ticket->increment('sold_quantity', $quantity);

                return $purchase;
            });

            Log::info('Ticket purchase completed successfully', [
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'event_id' => $event->id,
                'quantity' => $quantity,
                'total_price' => $purchase->total_price,
                'reference' => $purchase->purchase_reference
            ]);

            // Send confirmation email
            try {
                Mail::to($purchase->buyer_email)->send(new TicketPurchaseConfirmation([
                    'purchase' => $purchase,
                    'ticket' => $ticket,
                    'event' => $event,
                    'quantity' => $quantity
                ]));
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email: ' . $e->getMessage(), [
                    'purchase_id' => $purchase->id,
                    'buyer_email' => $purchase->buyer_email
                ]);
            }

            return redirect()->route('bezoeker.tickets.my-tickets')
                            ->with('success', 'Ticket(s) purchased successfully! Reference: ' . $purchase->purchase_reference);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for ticket purchase', [
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput()
                            ->with('error', 'Please check the form for errors and try again.');

        } catch (\Exception $e) {
            Log::error('Error processing ticket purchase: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'event_id' => $event->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while processing your purchase. Please try again or contact support.');
        }
    }

    /**
     * Show user's purchased tickets
     */
    public function myTickets(): View
    {
        try {
            $purchases = TicketPurchase::with(['event', 'ticket'])
                                      ->where('user_id', Auth::id())
                                      ->orderBy('purchased_at', 'desc')
                                      ->paginate(10);

            return view('bezoeker.tickets.my-tickets', compact('purchases'));

        } catch (\Exception $e) {
            Log::error('Error loading user tickets: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return view('bezoeker.tickets.my-tickets', ['purchases' => collect()])
                   ->with('error', 'Unable to load your tickets. Please try again.');
        }
    }
}
