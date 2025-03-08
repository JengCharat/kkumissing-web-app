<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Current Tenants Box -->
                <div class="bg-green-500 text-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">ผู้เช่าปัจจุบัน</h3>
                        <p class="text-3xl font-bold">{{ \App\Models\Tenant::count() }} ห้อง</p>
                        <p class="text-xl font-bold">รายเดือน {{ \App\Models\Tenant::where('tenant_type', 'monthly')->count() }} ห้อง</p>
                        <p class="text-xl font-bold">รายวัน {{ \App\Models\Tenant::where('tenant_type', 'daily')->count() }} ห้อง</p>
                        <div class="grid grid-cols-4 gap-1 mt-4">
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                        </div>
                    </div>
                    <a href="{{ route('admin.monthly-tenants') }}" class="block bg-green-600 hover:bg-green-700 text-center py-2 text-white transition duration-200">
                        More info
                    </a>
                </div>

                <!-- Available Rooms Box -->
                <div class="bg-red-500 text-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">จำนวนห้องพัก</h3>
                        <p class="text-3xl font-bold"> ห้องพักทั้งหมด {{ \App\Models\Room::count() }} รายการ</p>
                        <p class="text-xl font-bold"> ห้องพักที่ว่าง {{ \App\Models\Room::where('status', 'Available')->count() }} รายการ</p>
                        <p class="text-xl font-bold"> ห้องพักที่ไม่ว่าง {{ \App\Models\Room::where('status', 'Not Available')->count() }} รายการ</p>
                        <div class="grid grid-cols-4 gap-1 mt-4">
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                            <div class="h-2 bg-white bg-opacity-30 rounded"></div>
                        </div>
                    </div>
                    <a href="{{ route('admin.rooms') }}" class="block bg-red-600 hover:bg-red-700 text-center py-2 text-white transition duration-200">
                        More info
                    </a>
                </div>
            </div>
            <!-- Room Display -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4">ตารางรวมค่าบิลประจำเดือน</h3>
                <script
                src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
                </script>
                    <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
                <script>
                    const xValues = [@foreach ($month_date as $item)'{{$item}}',@endforeach];
                    const yValues =[@foreach ($monthly_totals as $item){{$item}},@endforeach];
                    new Chart("myChart", {
                    type: "bar",
                    data: {
                        labels: xValues,
                        datasets: [{
                        fill: false,
                        lineTension: 0,
                        backgroundColor: "rgba(0,0,255,1.0)",
                        borderColor: "rgba(0,0,255,0.1)",
                        data: yValues
                        }]
                    },
                    options: {
                        legend: {display: false},
                        scales: {
                        yAxes: [{ticks: {min: 1 , max:100000}}],
                        }
                    }
                    });
                </script>
                {{-- <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Room Status</h3>

                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-600 dark:text-gray-400">Selected Room:</h4>
                        <h1 id="room_ID_select" class="text-2xl font-bold text-blue-600 dark:text-blue-400"></h1>
                    </div>
                    <input type="hidden" name="roomNumber" id="room_ID_select2" value="">

                    <!-- Room Status Update Form -->
                    <div id="room_status_form" class="mb-4" style="display: none;">
                        <form method="POST" action="/admin/update-room-status">
                            @csrf
                            <input type="hidden" name="roomNumber" id="status_room_number">
                            <input type="hidden" name="status" id="room_status_value">
                            <div class="flex items-center">
                                <span class="mr-2">Room Status:</span>
                                <span id="current_room_status" class="px-2 py-1 rounded text-white mr-4"></span>
                                <x-button id="toggle_status_button" type="submit">
                                    Change Status
                                </x-button>
                            </div>
                        </form>
                    </div>

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
                </div> --}}
            </div>


            <!-- Meter Reading Update Form -->
            <div id="meter_reading_form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6" style="display: none;">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Update Meter Readings</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Currently editing room: <span id="meter_room_display" class="font-medium text-blue-600 dark:text-blue-400"></span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4" id="latest_meter_values">Latest values: No data available</p>
                    <form method="POST" action="/admin/update-meter-readings">
                        @csrf
                        <input type="hidden" name="roomNumber" id="meter_room_number">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="water_meter_start" value="Water Meter Start" />
                                <x-input id="water_meter_start" type="number" name="water_meter_start" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="water_meter_end" value="Water Meter End" />
                                <x-input id="water_meter_end" type="number" name="water_meter_end" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="electricity_meter_start" value="Electricity Meter Start" />
                                <x-input id="electricity_meter_start" type="number" name="electricity_meter_start" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="electricity_meter_end" value="Electricity Meter End" />
                                <x-input id="electricity_meter_end" type="number" name="electricity_meter_end" class="block mt-1 w-full" required />
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
                    document.getElementById('status_room_number').value = roomNumber;

                    // Show the meter reading form and room status form
                    document.getElementById('meter_reading_form').style.display = 'block';
                    document.getElementById('room_status_form').style.display = 'block';

                    // Get current meter readings for the room
                    const meterReadings = @json($meterReadings);
                    // Find the room first to get its ID
                    const rooms = [...@json($Lrooms), ...@json($Rrooms)];
                    const selectedRoom = rooms.find(room => room.roomNumber === roomNumber);

                    // Update room status display and form
                    if (selectedRoom) {
                        const statusElement = document.getElementById('current_room_status');
                        const statusValueInput = document.getElementById('room_status_value');
                        const toggleButton = document.getElementById('toggle_status_button');

                        if (selectedRoom.status === 'Available') {
                            statusElement.textContent = 'Available';
                            statusElement.className = 'px-2 py-1 rounded text-white mr-4 bg-green-500';
                            statusValueInput.value = 'Not Available';
                            toggleButton.textContent = 'Mark as Not Available';
                        } else {
                            statusElement.textContent = 'Not Available';
                            statusElement.className = 'px-2 py-1 rounded text-white mr-4 bg-red-500';
                            statusValueInput.value = 'Available';
                            toggleButton.textContent = 'Mark as Available';
                        }
                    }

                    if (selectedRoom) {
                        const roomReading = meterReadings.find(reading => reading.room_id === selectedRoom.roomID);

                        if (roomReading && roomReading.meterdetails) {
                            const waterStart = roomReading.meterdetails.water_meter_start || '';
                            const waterEnd = roomReading.meterdetails.water_meter_end || '';
                            const electricityStart = roomReading.meterdetails.electricity_meter_start || '';
                            const electricityEnd = roomReading.meterdetails.electricity_meter_end || '';

                            // Update form values
                            document.getElementById('water_meter_start').value = waterStart;
                            document.getElementById('water_meter_end').value = waterEnd;
                            document.getElementById('electricity_meter_start').value = electricityStart;
                            document.getElementById('electricity_meter_end').value = electricityEnd;

                            // Update the latest values display
                            document.getElementById('latest_meter_values').innerHTML =
                                `Latest values: Water (${waterStart} → ${waterEnd}), Electricity (${electricityStart} → ${electricityEnd})`;
                        } else {
                            // Clear the form if no readings exist
                            document.getElementById('water_meter_start').value = '';
                            document.getElementById('water_meter_end').value = '';
                            document.getElementById('electricity_meter_start').value = '';
                            document.getElementById('electricity_meter_end').value = '';

                            // Update the latest values display
                            document.getElementById('latest_meter_values').textContent = 'Latest values: No data available';
                        }
                    } else {
                        // Clear the form if no readings exist
                        document.getElementById('water_meter_start').value = '';
                        document.getElementById('water_meter_end').value = '';
                        document.getElementById('electricity_meter_start').value = '';
                        document.getElementById('electricity_meter_end').value = '';

                        // Update the latest values display
                        document.getElementById('latest_meter_values').textContent = 'Latest values: No data available';
                    }
                }
            </script>
        </div>
    </div>
</x-admin-layout>
