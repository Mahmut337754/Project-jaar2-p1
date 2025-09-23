<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket: ') . $ticket->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Selection -->
                            <div class="md:col-span-2">
                                <label for="event_id" class="block text-sm font-medium text-gray-700">Event</label>
                                <select name="event_id" id="event_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        required>
                                    <option value="">Select an Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" 
                                                {{ old('event_id', $ticket->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }} - {{ $event->start_date->format('M j, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ticket Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Ticket Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $ticket->name) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Zaterdag Early Access" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Describe the ticket type..." required>{{ old('description', $ticket->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Day -->
                            <div>
                                <label for="day" class="block text-sm font-medium text-gray-700">Day</label>
                                <select name="day" id="day" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        required>
                                    <option value="">Select Day</option>
                                    <option value="saturday" {{ old('day', $ticket->day) === 'saturday' ? 'selected' : '' }}>Saturday</option>
                                    <option value="sunday" {{ old('day', $ticket->day) === 'sunday' ? 'selected' : '' }}>Sunday</option>
                                    <option value="both" {{ old('day', $ticket->day) === 'both' ? 'selected' : '' }}>Both Days</option>
                                </select>
                                @error('day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Admission Time -->
                            <div>
                                <label for="admission_time" class="block text-sm font-medium text-gray-700">Admission Time</label>
                                <input type="time" name="admission_time" id="admission_time" 
                                       value="{{ old('admission_time', $ticket->admission_time ? $ticket->admission_time->format('H:i') : '11:00') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('admission_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (€)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $ticket->price) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       step="0.01" min="0" placeholder="15.00" required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Quantity -->
                            <div>
                                <label for="total_quantity" class="block text-sm font-medium text-gray-700">Total Quantity</label>
                                <input type="number" name="total_quantity" id="total_quantity" value="{{ old('total_quantity', $ticket->total_quantity) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       min="1" placeholder="500" required>
                                @error('total_quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ticket->purchases()->sum('quantity') > 0)
                                    <p class="mt-1 text-sm text-yellow-600">
                                        Warning: {{ $ticket->purchases()->sum('quantity') }} tickets have already been sold.
                                    </p>
                                @endif
                            </div>

                            <!-- Features -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Features (Optional)</label>
                                <div class="space-y-2">
                                    @php
                                        $currentFeatures = old('features', $ticket->features ?? []);
                                    @endphp
                                    <label class="flex items-center">
                                        <input type="checkbox" name="features[]" value="Early access" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array('Early access', $currentFeatures) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Early Access</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="features[]" value="VIP treatment" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array('VIP treatment', $currentFeatures) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">VIP Treatment</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="features[]" value="Best selection" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array('Best selection', $currentFeatures) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Best Selection</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="features[]" value="Full day access" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array('Full day access', $currentFeatures) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Full Day Access</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="features[]" value="Last chance deals" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ in_array('Last chance deals', $currentFeatures) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">Last Chance Deals</span>
                                    </label>
                                </div>
                                @error('features')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.events.show', $ticket->event) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>