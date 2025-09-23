<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Purchase Ticket - ') . Str::limit($ticket->name, 20) }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm sm:text-base">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <!-- Ticket Details -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold mb-4">Ticket Details</h3>
                        
                        <!-- Event Info -->
                        <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2 text-sm sm:text-base">{{ $event->name }}</h4>
                            <div class="text-xs sm:text-sm text-gray-600 space-y-1">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">{{ $event->start_date->format('M j, Y') }} - {{ $event->end_date->format('M j, Y') }}</span>
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
                                    <span class="truncate">{{ $event->start_time ? $event->start_time->format('H:i') : 'TBD' }} - {{ $event->end_time ? $event->end_time->format('H:i') : 'TBD' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Info -->
                        <div class="border rounded-lg p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3">
                                <h5 class="text-lg font-semibold text-gray-900">{{ $ticket->name }}</h5>
                                <span class="text-2xl font-bold text-orange-600">€{{ number_format($ticket->price, 2) }}</span>
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ $ticket->description }}</p>
                            
                            <div class="text-sm text-gray-600 mb-3">
                                <div><strong>Day:</strong> {{ ucfirst($ticket->day) }}</div>
                                <div><strong>Admission Time:</strong> {{ $ticket->admission_time ? $ticket->admission_time->format('H:i') : 'TBD' }}</div>
                                <div><strong>Available:</strong> {{ $ticket->available_quantity }} tickets</div>
                            </div>

                            @if($ticket->features)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($ticket->features as $feature)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-6">Purchase Information</h3>
                        
                        <form action="{{ route('bezoeker.tickets.store', [$event, $ticket]) }}" method="POST" id="purchaseForm">
                            @csrf
                            
                            <!-- Quantity -->
                            <div class="mb-6">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Number of Tickets
                                </label>
                                <select name="quantity" id="quantity" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                        onchange="updateTotal()" required>
                                    @for($i = 1; $i <= min(10, $ticket->available_quantity); $i++)
                                        <option value="{{ $i }}" {{ old('quantity') == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'ticket' : 'tickets' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Buyer Information -->
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="buyer_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="buyer_name" id="buyer_name" 
                                               value="{{ old('buyer_name', Auth::user()->name) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                               required>
                                        @error('buyer_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="buyer_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" name="buyer_email" id="buyer_email" 
                                               value="{{ old('buyer_email', Auth::user()->email) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                               required>
                                        @error('buyer_email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="buyer_phone" class="block text-sm font-medium text-gray-700">Phone Number (Optional)</label>
                                        <input type="tel" name="buyer_phone" id="buyer_phone" 
                                               value="{{ old('buyer_phone') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                               placeholder="+31 6 12345678">
                                        @error('buyer_phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Order Summary</h4>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">{{ $ticket->name }}</span>
                                    <span class="font-medium">€{{ number_format($ticket->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Quantity:</span>
                                    <span class="font-medium" id="summaryQuantity">1</span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between items-center text-lg font-bold">
                                    <span>Total:</span>
                                    <span class="text-orange-600" id="totalPrice">€{{ number_format($ticket->price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <a href="{{ route('bezoeker.tickets.show', $event) }}" 
                                   class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded text-center">
                                    Back to Tickets
                                </a>
                                <button type="submit" 
                                        class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded">
                                    Complete Purchase
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const pricePerTicket = {{ $ticket->price }};
            const total = quantity * pricePerTicket;
            
            document.getElementById('summaryQuantity').textContent = quantity;
            document.getElementById('totalPrice').textContent = '€' + total.toFixed(2);
        }
    </script>
</x-app-layout>