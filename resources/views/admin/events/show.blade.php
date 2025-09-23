<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.events.edit', $event) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Event
                </a>
                <a href="{{ route('admin.events.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Event Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Event Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <span class="ml-2 px-2 py-1 rounded text-sm 
                                        {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 
                                           ($event->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Location:</span>
                                    <span class="ml-2">{{ $event->location }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Start Date:</span>
                                    <span class="ml-2">{{ $event->start_date->format('l, F j, Y') }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">End Date:</span>
                                    <span class="ml-2">{{ $event->end_date->format('l, F j, Y') }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Time:</span>
                                    <span class="ml-2">{{ $event->start_time ? $event->start_time->format('H:i') : 'TBD' }} - {{ $event->end_time ? $event->end_time->format('H:i') : 'TBD' }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Base Price:</span>
                                    <span class="ml-2">€{{ number_format($event->base_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium text-gray-700">Total Tickets:</span>
                                    <span class="ml-2">{{ $event->tickets->count() }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Tickets Sold:</span>
                                    <span class="ml-2">{{ $event->ticketPurchases->sum('quantity') }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Total Revenue:</span>
                                    <span class="ml-2">€{{ number_format($event->totalRevenue(), 2) }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-700">Available Capacity:</span>
                                    <span class="ml-2">{{ $event->tickets->sum('total_quantity') - $event->ticketPurchases->sum('quantity') }} / {{ $event->tickets->sum('total_quantity') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700">{{ $event->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Tickets Management -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Event Tickets</h3>
                        <a href="{{ route('admin.tickets.create', ['event' => $event->id]) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Add New Ticket Type
                        </a>
                    </div>

                    @if($event->tickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ticket Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Day & Time
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sold / Total
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revenue
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($event->tickets as $ticket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $ticket->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $ticket->description }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ ucfirst($ticket->day) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $ticket->admission_time ? $ticket->admission_time->format('H:i') : 'TBD' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($ticket->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $sold = $ticket->purchases->sum('quantity');
                                                    $total = $ticket->total_quantity;
                                                    $percentage = $total > 0 ? ($sold / $total) * 100 : 0;
                                                @endphp
                                                <div class="text-sm text-gray-900">{{ $sold }} / {{ $total }}</div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($ticket->purchases->sum('quantity') * $ticket->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this ticket type?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No tickets have been created for this event yet.</p>
                            <a href="{{ route('admin.tickets.create', ['event' => $event->id]) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Create First Ticket Type
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Purchases -->
            @if($event->ticketPurchases->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Recent Purchases</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ticket Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Price
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Purchase Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($event->ticketPurchases->take(10) as $purchase)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $purchase->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $purchase->ticket->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $purchase->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($purchase->total_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $purchase->created_at->format('M j, Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>