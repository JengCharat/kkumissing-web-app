<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('เช่าห้องรายวัน') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.monthly-rooms') }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">ห้องพักรายเดือน</a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.daily-rooms') }}" class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">ห้องพักรายวัน</a>
                    </li>
                </ul>
            </div>

            <!-- Room Display -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">รายการห้องพักรายวัน</h3>
                        <div>
                            <x-button>
                                + เพิ่มการจอง
                            </x-button>
                        </div>
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

            <!-- Selected Room Details (Initially Hidden) -->
            <div id="room_details" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="display: none;">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">จองห้อง <span id="selected_room_number"></span></h3>

                    <div class="mt-4">
                        <h4 class="font-medium mb-2">ข้อมูลการจอง</h4>
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="guest_name" value="ชื่อผู้เข้าพัก" />
                                    <x-input id="guest_name" type="text" class="block mt-1 w-full" placeholder="ชื่อ-นามสกุล" />
                                </div>
                                <div>
                                    <x-label for="guest_phone" value="เบอร์โทรศัพท์" />
                                    <x-input id="guest_phone" type="text" class="block mt-1 w-full" placeholder="0xx-xxx-xxxx" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="check_in_date" value="วันที่เข้าพัก" />
                                    <x-input id="check_in_date" type="date" class="block mt-1 w-full" />
                                </div>
                                <div>
                                    <x-label for="check_out_date" value="วันที่ออก" />
                                    <x-input id="check_out_date" type="date" class="block mt-1 w-full" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="daily_rate" value="ราคาต่อวัน (บาท)" />
                                    <x-input id="daily_rate" type="number" class="block mt-1 w-full" value="600" />
                                </div>
                                <div>
                                    <x-label for="num_guests" value="จำนวนผู้เข้าพัก" />
                                    <x-input id="num_guests" type="number" class="block mt-1 w-full" value="1" min="1" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-button>
                                    บันทึกการจอง
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function select_this_room(roomNumber) {
                    // Show room details section
                    document.getElementById('room_details').style.display = 'block';
                    document.getElementById('selected_room_number').textContent = roomNumber;

                    // Set default dates (today and tomorrow)
                    const today = new Date();
                    const tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);

                    document.getElementById('check_in_date').value = formatDate(today);
                    document.getElementById('check_out_date').value = formatDate(tomorrow);
                }

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
            </script>
        </div>
    </div>
</x-admin-layout>
