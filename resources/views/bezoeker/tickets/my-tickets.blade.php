<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($purchases->count() > 0)
                <div class="space-y-6">
                    @foreach($purchases as $purchase)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $purchase->event->name }}</h3>
                                        <p class="text-sm text-gray-600">Reference: {{ $purchase->purchase_reference }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            {{ $purchase->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                               ($purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Event Details -->
                                    <div class="lg:col-span-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Event Info -->
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Event Details</h4>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $purchase->event->start_date->format('M j, Y') }} - {{ $purchase->event->end_date->format('M j, Y') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $purchase->event->location }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $purchase->event->start_time ? $purchase->event->start_time->format('H:i') : 'TBD' }} - {{ $purchase->event->end_time ? $purchase->event->end_time->format('H:i') : 'TBD' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ticket Info -->
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Ticket Information</h4>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <div><strong>Type:</strong> {{ $purchase->ticket->name }}</div>
                                                    <div><strong>Day:</strong> {{ ucfirst($purchase->ticket->day) }}</div>
                                                    <div><strong>Admission:</strong> {{ $purchase->ticket->admission_time ? $purchase->ticket->admission_time->format('H:i') : 'TBD' }}</div>
                                                    <div><strong>Quantity:</strong> {{ $purchase->quantity }} {{ $purchase->quantity == 1 ? 'ticket' : 'tickets' }}</div>
                                                </div>

                                                @if($purchase->ticket->features)
                                                    <div class="mt-3">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($purchase->ticket->features as $feature)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ $feature }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Purchase Summary -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Purchase Summary</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Unit Price:</span>
                                                <span>€{{ number_format($purchase->unit_price, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Quantity:</span>
                                                <span>{{ $purchase->quantity }}</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="flex justify-between font-semibold">
                                                <span>Total Paid:</span>
                                                <span class="text-orange-600">€{{ number_format($purchase->total_price, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t text-xs text-gray-500">
                                            <div><strong>Purchased:</strong> {{ $purchase->purchased_at->format('M j, Y H:i') }}</div>
                                            <div><strong>Buyer:</strong> {{ $purchase->buyer_name }}</div>
                                            <div><strong>Email:</strong> {{ $purchase->buyer_email }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-6 flex justify-end space-x-3">
                                    @if($purchase->status === 'confirmed')
                                        <button onclick="window.print()" 
                                                class="bg-gray-600 hover:bg-gray-700 font-bold py-2 px-4 rounded text-sm">
                                            Print Tickets
                                        </button>
                                        <button onclick="downloadQR('{{ $purchase->purchase_reference }}')" 
                                                class="bg-orange-600 hover:bg-orange-700 font-bold py-2 px-4 rounded text-sm">
                                            Download QR Code
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $purchases->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Tickets Yet</h3>
                        <p class="text-gray-600 mb-6">You haven't purchased any tickets yet. Browse our upcoming events and get your tickets!</p>
                        <a href="{{ route('bezoeker.tickets') }}" 
                           class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Browse Events
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function downloadQR(reference) {
            // This would typically generate and download a QR code
            alert('QR Code for ticket reference: ' + reference + ' would be downloaded here.');
        }
    </script>
</x-app-layout>