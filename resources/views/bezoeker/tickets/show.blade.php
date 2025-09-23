<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ Str::limit($event->name, 30) }} - Select Tickets
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 lg:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Event Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-4 sm:mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ $event->name }}</h3>
                            <p class="text-gray-600 mb-4 text-sm sm:text-base">{{ $event->description }}</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">{{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saturday Tickets -->
            @if($saturdayTickets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-4 sm:mb-6">
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900">Zaterdag Tickets</h4>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($saturdayTickets as $ticket)
                        <div class="border rounded-lg p-3 sm:p-4 {{ $ticket->isAvailable() ? 'border-gray-300 hover:border-orange-500 hover:shadow-md transition-all' : 'border-gray-200 bg-gray-50' }}" data-ticket-id="{{ $ticket->id }}">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                <h5 class="font-semibold text-gray-900 text-sm sm:text-base mb-1 sm:mb-0">{{ $ticket->name }}</h5>
                                <span class="text-base sm:text-lg font-bold text-orange-600">{{ $ticket->formatted_price }}</span>
                            </div>
                            
                            <p class="text-xs sm:text-sm text-gray-600 mb-3 leading-relaxed">{{ $ticket->description }}</p>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 text-xs sm:text-sm">
                                @if($ticket->isAvailable())
                                    <span class="availability-count text-green-600 order-2 sm:order-1">{{ $ticket->available_quantity }} available</span>
                                    <a href="{{ route('bezoeker.tickets.purchase', [$event, $ticket]) }}" 
                                       class="buy-button w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm text-center transition-colors duration-200 order-1 sm:order-2">
                                        Buy Now
                                    </a>
                                @else
                                    <span class="availability-count text-red-600 order-2 sm:order-1">Sold Out</span>
                                    <button disabled class="buy-button w-full sm:w-auto bg-gray-400 text-white font-bold py-2 px-4 rounded text-sm cursor-not-allowed order-1 sm:order-2">
                                        Sold Out
                                    </button>
                                @endif
                            </div>

                            @if($ticket->features)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($ticket->features as $feature)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $feature }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Sunday Tickets -->
            @if($sundayTickets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900">Zondag Tickets</h4>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($sundayTickets as $ticket)
                        <div class="border rounded-lg p-3 sm:p-4 {{ $ticket->isAvailable() ? 'border-gray-300 hover:border-orange-500 hover:shadow-md transition-all' : 'border-gray-200 bg-gray-50' }}" data-ticket-id="{{ $ticket->id }}">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                <h5 class="font-semibold text-gray-900 text-sm sm:text-base mb-1 sm:mb-0">{{ $ticket->name }}</h5>
                                <span class="text-base sm:text-lg font-bold text-orange-600">{{ $ticket->formatted_price }}</span>
                            </div>
                            
                            <p class="text-xs sm:text-sm text-gray-600 mb-3 leading-relaxed">{{ $ticket->description }}</p>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 text-xs sm:text-sm">
                                @if($ticket->isAvailable())
                                    <span class="availability-count text-green-600 order-2 sm:order-1">{{ $ticket->available_quantity }} available</span>
                                    <a href="{{ route('bezoeker.tickets.purchase', [$event, $ticket]) }}" 
                                       class="buy-button w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm text-center transition-colors duration-200 order-1 sm:order-2">
                                        Buy Now
                                    </a>
                                @else
                                    <span class="availability-count text-red-600 order-2 sm:order-1">Sold Out</span>
                                    <button disabled class="buy-button w-full sm:w-auto bg-gray-400 text-white font-bold py-2 px-4 rounded text-sm cursor-not-allowed order-1 sm:order-2">
                                        Sold Out
                                    </button>
                                @endif
                            </div>

                            @if($ticket->features)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($ticket->features as $feature)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $feature }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-4 sm:mt-6 text-center">
                <a href="{{ route('bezoeker.tickets') }}" 
                   class="inline-block w-full sm:w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded text-sm sm:text-base transition-colors duration-200">
                    ← Back to Events
                </a>
            </div>
        </div>
    </div>

    <script>
        // Set global event ID for real-time updates
        window.eventId = {{ $event->id }};
        
        // Real-time ticket availability updates
        document.addEventListener('DOMContentLoaded', function() {
            
            // Function to update ticket availability
            function updateTicketAvailability() {
                const eventId = window.eventId;
                if (!eventId) return;

                fetch(`/api/events/${eventId}/tickets/availability`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update availability for each ticket
                            data.tickets.forEach(ticket => {
                                const ticketCard = document.querySelector(`[data-ticket-id="${ticket.id}"]`);
                                if (ticketCard) {
                                    const availabilitySpan = ticketCard.querySelector('.availability-count');
                                    const buyButton = ticketCard.querySelector('.buy-button');
                                    
                                    if (availabilitySpan) {
                                        availabilitySpan.textContent = `${ticket.available_quantity} available`;
                                        
                                        // Update styling based on availability
                                        if (ticket.available_quantity === 0) {
                                            availabilitySpan.textContent = 'Sold Out';
                                            availabilitySpan.className = 'availability-count text-red-600';
                                            if (buyButton) {
                                                buyButton.disabled = true;
                                                buyButton.className = 'w-full sm:w-auto bg-gray-400 text-white font-bold py-2 px-4 rounded text-sm cursor-not-allowed order-1 sm:order-2';
                                                buyButton.textContent = 'Sold Out';
                                            }
                                        } else if (ticket.available_quantity <= 10) {
                                            availabilitySpan.className = 'availability-count text-yellow-600';
                                        } else {
                                            availabilitySpan.className = 'availability-count text-green-600';
                                        }
                                    }
                                }
                            });
                        }
                    })
                    .catch(error => console.log('Availability update failed:', error));
            }

            // Update availability every 30 seconds
            setInterval(updateTicketAvailability, 30000);

            // Also update when user comes back to the tab
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    updateTicketAvailability();
                }
            });
        });
    </script>
</x-app-layout>