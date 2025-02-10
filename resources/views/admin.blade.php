<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Room Display -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Room Status</h3>

                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-600 dark:text-gray-400">Selected Room:</h4>
                        <h1 id="room_ID_select" class="text-2xl font-bold text-blue-600 dark:text-blue-400"></h1>
                    </div>
                    <input type="hidden" name="roomNumber" id="room_ID_select2" value="">

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">Left Rooms</h4>
                        <div class="grid grid-cols-6 gap-2">
                            @foreach ($Lrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    <button onclick="select_this_room('{{ $item->roomNumber }}')"
                                            class="p-2 text-center rounded {{ $item->status == 'Available' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white">
                                        {{ $item->roomNumber }}
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">Right Rooms</h4>
                        <div class="grid grid-cols-6 gap-2">
                            @foreach ($Rrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    <button onclick="select_this_room('{{ $item->roomNumber }}')"
                                            class="p-2 text-center rounded {{ $item->status == 'Available' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white">
                                        {{ $item->roomNumber }}
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unit Price Update Form -->
            <div id="price_update_form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Update Unit Prices</h3>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="/admin/update-unit-prices">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="unit_price_water" value="Water Unit Price (per unit)" />
                                <x-input id="unit_price_water" type="number" step="0.01" name="unit_price_water" value="{{ $unit_price_water }}" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="unit_price_electricity" value="Electricity Unit Price (per unit)" />
                                <x-input id="unit_price_electricity" type="number" step="0.01" name="unit_price_electricity" value="{{ $unit_price_electricity }}" class="block mt-1 w-full" required />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-button>
                                Update Prices
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Meter Reading Update Form -->
            <div id="meter_reading_form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6" style="display: none;">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Update Meter Readings</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Currently editing room: <span id="meter_room_display" class="font-medium text-blue-600 dark:text-blue-400"></span></p>
                    <form method="POST" action="/admin/update-meter-readings">
                        @csrf
                        <input type="hidden" name="roomNumber" id="meter_room_number">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="water_meter_start" value="Water Meter Start" />
                                <x-input id="water_meter_start" type="number" step="0.01" name="water_meter_start" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="water_meter_end" value="Water Meter End" />
                                <x-input id="water_meter_end" type="number" step="0.01" name="water_meter_end" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="electricity_meter_start" value="Electricity Meter Start" />
                                <x-input id="electricity_meter_start" type="number" step="0.01" name="electricity_meter_start" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="electricity_meter_end" value="Electricity Meter End" />
                                <x-input id="electricity_meter_end" type="number" step="0.01" name="electricity_meter_end" class="block mt-1 w-full" required />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-button>
                                Update Meter Readings
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function select_this_room(roomNumber) {
                    document.getElementById('room_ID_select').textContent = `Room ${roomNumber}`;
                    document.getElementById('meter_room_display').textContent = roomNumber;
                    document.getElementById('room_ID_select2').value = roomNumber;
                    document.getElementById('meter_room_number').value = roomNumber;

                    // Show the meter reading form
                    document.getElementById('meter_reading_form').style.display = 'block';

                    // Get current meter readings for the room
                    const meterReadings = @json($meterReadings);
                    const roomReading = meterReadings.find(reading => reading.room_id === roomNumber);

                    if (roomReading && roomReading.meterdetails) {
                        document.getElementById('water_meter_start').value = roomReading.meterdetails.water_meter_start || '';
                        document.getElementById('water_meter_end').value = roomReading.meterdetails.water_meter_end || '';
                        document.getElementById('electricity_meter_start').value = roomReading.meterdetails.electricity_meter_start || '';
                        document.getElementById('electricity_meter_end').value = roomReading.meterdetails.electricity_meter_end || '';
                    } else {
                        // Clear the form if no readings exist
                        document.getElementById('water_meter_start').value = '';
                        document.getElementById('water_meter_end').value = '';
                        document.getElementById('electricity_meter_start').value = '';
                        document.getElementById('electricity_meter_end').value = '';
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>
