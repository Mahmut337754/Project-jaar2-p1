<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $stats['my_tickets'] }}</div>
                        </div>
                        <div class="text-sm text-gray-600">My Tickets</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $stats['upcoming_events'] }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Upcoming Events</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">€{{ number_format($stats['total_spent'], 2) }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Total Spent</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">My Tickets</h3>
                        <div class="space-y-2">
                            <a href="{{ route('bezoeker.tickets') }}" class="block w-full text-left px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                View My Tickets
                            </a>
                            <button class="block w-full text-left px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition ease-in-out duration-150">
                                Buy New Tickets
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Account</h3>
                        <div class="space-y-2">
                            <a href="{{ route('profile.edit') }}" class="block w-full text-left px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                Edit Profile
                            </a>
                            <button class="block w-full text-left px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 transition ease-in-out duration-150">
                                Purchase History
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Welcome to Sneakerness Rotterdam!</h3>
                        <p class="text-gray-600">Join the ultimate sneaker event at Van Nellefabriek on November 11-12, 2023. Discover exclusive sneakers, meet the community, and enjoy the perfect mix of Art, Sport, Fashion, and Music!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>