<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดการห้องพัก') }}
        </h2>
    </x-slot>

    <div class="py-6">
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
                </div>
            </div>

            <script>
                function select_this_room(roomNumber) {
                    document.getElementById('room_ID_select').textContent = `Room ${roomNumber}`;
                    document.getElementById('room_ID_select2').value = roomNumber;
                    document.getElementById('status_room_number').value = roomNumber;

                    // Show the room status form
                    document.getElementById('room_status_form').style.display = 'block';

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
                }
            </script>
        </div>
    </div>
</x-admin-layout>
