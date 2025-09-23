<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ticket Management') }}
            </h2>
            <a href="{{ route('admin.tickets.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Create New Ticket Type
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ticket Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event
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
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $ticket->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($ticket->description, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <a href="{{ route('admin.events.show', $ticket->event) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        {{ $ticket->event->name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $ticket->event->start_date->format('M j, Y') }}
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
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}%</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($sold * $ticket->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                                       class="text-blue-600 hover:text-blue-900">View</a>
                                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    @if($ticket->purchases->count() === 0)
                                                        <form action="{{ route('admin.tickets.destroy', $ticket) }}" 
                                                              method="POST" 
                                                              class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this ticket type?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">Delete</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new ticket type.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.tickets.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    New Ticket Type
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            @if($tickets->count() > 0)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Ticket Types</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $tickets->total() }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Tickets Sold</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $tickets->sum(function($ticket) { return $ticket->purchases->sum('quantity'); }) }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Revenue</div>
                            <div class="text-2xl font-bold text-gray-900">
                                €{{ number_format($tickets->sum(function($ticket) { 
                                    return $ticket->purchases->sum('quantity') * $ticket->price; 
                                }), 2) }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Available Capacity</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $tickets->sum('total_quantity') - $tickets->sum(function($ticket) { return $ticket->purchases->sum('quantity'); }) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>