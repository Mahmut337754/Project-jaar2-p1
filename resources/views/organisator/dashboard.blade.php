<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organisator Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $stats['total_events'] }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Total Events</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $stats['total_tickets_sold'] }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Tickets Sold</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $stats['total_stands_rented'] }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Stands Rented</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">€{{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="text-sm text-gray-600">Total Revenue</div>
                    </div>
                </div>
            </div>

            <!-- Management Sections -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Event Management</h3>
                        <div class="space-y-2">
                            <a href="{{ route('organisator.events') }}" class="block w-full text-left px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                Manage Events
                            </a>
                            <button class="block w-full text-left px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition ease-in-out duration-150">
                                Create New Event
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Ticket Management</h3>
                        <div class="space-y-2">
                            <a href="{{ route('organisator.tickets') }}" class="block w-full text-left px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                View All Tickets
                            </a>
                            <button class="block w-full text-left px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 transition ease-in-out duration-150">
                                Ticket Reports
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Stand Management</h3>
                        <div class="space-y-2">
                            <a href="{{ route('organisator.stands') }}" class="block w-full text-left px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                Manage Stands
                            </a>
                            <button class="block w-full text-left px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
                                Stand Reports
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>