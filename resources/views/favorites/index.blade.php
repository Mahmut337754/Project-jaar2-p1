<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Favorite Events') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        You have <strong>{{ $favoriteEvents->count() }}</strong> favorite events
                    </p>
                    <a href="{{ route('bezoeker.tickets') }}" 
                       class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        Browse All Events →
                    </a>
                </div>
            </div>

            @if($favoriteEvents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($favoriteEvents as $event)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-300">
                        @if($event->image_url)
                        <div class="h-32 sm:h-40 md:h-48 bg-cover bg-center" style="background-image: url('{{ $event->image_url }}')"></div>
                        @endif
                        
                        <div class="p-4 sm:p-6">
                            <div class="mb-3 sm:mb-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight flex-1 pr-2">{{ $event->name }}</h3>
                                    <!-- Favorite Button -->
                                    <button onclick="toggleFavorite({{ $event->id }}, this)"
                                            class="favorite-btn flex-shrink-0 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                            data-event-id="{{ $event->id }}"
                                            data-favorited="true">
                                        <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">{{ Str::limit($event->description, 80) }}</p>
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
                                   class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded text-sm sm:text-base text-center transition-colors duration-200 order-1 sm:order-2">
                                    🎫 BUY TICKETS
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No favorite events yet</h3>
                        <p class="text-sm text-gray-500 mb-4">Start exploring events and add them to your favorites!</p>
                        <a href="{{ route('bezoeker.tickets') }}" 
                           class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                            Browse Events
                        </a>
                    </div>
                </div>
            @endif
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
                        // Remove the card from favorites page
                        button.closest('.bg-white').style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Failed to toggle favorite:', error);
            }
        }
    </script>
</x-app-layout>