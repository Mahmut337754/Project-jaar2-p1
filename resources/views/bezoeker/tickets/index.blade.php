<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Events') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm sm:text-base">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm sm:text-base">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6">
                <form method="GET" action="{{ route('bezoeker.tickets') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search by name -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Events</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Event name..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>

                        <!-- Date filter -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <select id="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                <option value="">All dates</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="tomorrow" {{ request('date') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                                <option value="this_week" {{ request('date') == 'this_week' ? 'selected' : '' }}>This week</option>
                                <option value="this_month" {{ request('date') == 'this_month' ? 'selected' : '' }}>This month</option>
                                <option value="next_month" {{ request('date') == 'next_month' ? 'selected' : '' }}>Next month</option>
                            </select>
                        </div>

                        <!-- Price range filter -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                            <select id="price" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                <option value="">All prices</option>
                                <option value="0-25" {{ request('price') == '0-25' ? 'selected' : '' }}>€0 - €25</option>
                                <option value="25-50" {{ request('price') == '25-50' ? 'selected' : '' }}>€25 - €50</option>
                                <option value="50-100" {{ request('price') == '50-100' ? 'selected' : '' }}>€50 - €100</option>
                                <option value="100+" {{ request('price') == '100+' ? 'selected' : '' }}>€100+</option>
                            </select>
                        </div>

                        <!-- Location filter -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ request('location') }}"
                                   placeholder="City..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button type="submit" 
                                class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 font-bold py-2 px-6 rounded transition-colors duration-200">
                            🔍 Search Events
                        </button>
                        <a href="{{ route('bezoeker.tickets') }}" 
                           class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded text-center transition-colors duration-200">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <p class="text-sm text-gray-600">
                        @if(request()->hasAny(['search', 'date', 'price', 'location']))
                            Found <strong>{{ $events->count() }}</strong> events matching your filters
                        @else
                            Showing <strong>{{ $events->count() }}</strong> available events
                        @endif
                    </p>
                    
                    @if(request()->hasAny(['search', 'date', 'price', 'location']))
                        <div class="text-xs text-gray-500">
                            Active filters: 
                            @if(request('search')) <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">{{ request('search') }}</span> @endif
                            @if(request('date')) <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', request('date'))) }}</span> @endif
                            @if(request('price')) <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">€{{ request('price') }}</span> @endif
                            @if(request('location')) <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">{{ request('location') }}</span> @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($events as $event)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-300">
                    @if($event->image_url)
                    <div class="h-32 sm:h-40 md:h-48 bg-cover bg-center" style="background-image: url('{{ $event->image_url }}')"></div>
                    @endif
                    
                    <div class="p-4 sm:p-6">
                        <div class="mb-3 sm:mb-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight flex-1 pr-2">{{ $event->name }}</h3>
                                @auth
                                <!-- Favorite Button -->
                                <button onclick="toggleFavorite({{ $event->id }}, this)"
                                        class="favorite-btn flex-shrink-0 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                        data-event-id="{{ $event->id }}"
                                        data-favorited="{{ Auth::user()->hasFavorited($event) ? 'true' : 'false' }}">
                                    <svg class="w-5 h-5 {{ Auth::user()->hasFavorited($event) ? 'text-red-500 fill-current' : 'text-gray-400' }}" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                                @endauth
                            </div>
                            <p class="text-gray-600 text-xs sm:text-sm mt-1 leading-relaxed">{{ Str::limit($event->description, 80) }}</p>
                        </div>

                        <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
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

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                            <div class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1">
                                {{ $event->activeTickets->count() }} ticket types available
                            </div>
                            <a href="{{ route('bezoeker.tickets.show', $event) }}" 
                               class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 font-bold py-2 sm:py-3 px-4 sm:px-6 rounded text-sm sm:text-base text-center border-2 border-red-500 transition-colors duration-200 order-1 sm:order-2">
                                🎫 BUY TICKETS
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-3 text-center py-8 sm:py-12">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">No events available</h3>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">Check back later for upcoming events!</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        async function toggleFavorite(eventId, button) {
            try {
                const response = await fetch(`/favorites/${eventId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const svg = button.querySelector('svg');
                    if (data.is_favorited) {
                        svg.classList.add('text-red-500', 'fill-current');
                        svg.classList.remove('text-gray-400');
                        button.dataset.favorited = 'true';
                    } else {
                        svg.classList.remove('text-red-500', 'fill-current');
                        svg.classList.add('text-gray-400');
                        button.dataset.favorited = 'false';
                    }
                }
            } catch (error) {
                console.error('Failed to toggle favorite:', error);
            }
        }
    </script>
</x-app-layout>