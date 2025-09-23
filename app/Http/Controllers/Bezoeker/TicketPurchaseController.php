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
        if (!$ticket->isAvailable() || $ticket->event_id !== $event->id) {
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
            return redirect()->back()->with('error', 'Not enough tickets available. Only ' . $ticket->available_quantity . ' tickets left.');
        }

        try {
            DB::transaction(function () use ($validated, $event, $ticket, $quantity) {
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
            });

            return redirect()->route('bezoeker.tickets.my-tickets')
                           ->with('success', 'Tickets purchased successfully! Check your email for confirmation.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during purchase. Please try again.');
        }
    }

    /**
     * Show user's purchased tickets
     */
    public function myTickets(): View
    {
        $purchases = TicketPurchase::with(['event', 'ticket'])
                                  ->where('user_id', Auth::id())
                                  ->orderBy('purchased_at', 'desc')
                                  ->paginate(10);

        return view('bezoeker.tickets.my-tickets', compact('purchases'));
    }
}
