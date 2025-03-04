<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ออกบิลรายเดือน') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.monthly-rooms') }}" class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">ห้องพักรายเดือน</a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.daily-rooms') }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">ห้องพักรายวัน</a>
                    </li>
                </ul>
            </div>

            <!-- Room Display -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">รายการห้องพักรายเดือน</h3>
                        <div>
                            <x-button>
                                + เพิ่มบิลใหม่
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
                    <h3 class="text-lg font-semibold mb-4">รายละเอียดห้อง <span id="selected_room_number"></span></h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-medium mb-2">ข้อมูลผู้เช่า</h4>
                            <p>ชื่อ: <span id="tenant_name">-</span></p>
                            <p>เบอร์โทร: <span id="tenant_phone">-</span></p>
                        </div>
                        <div>
                            <h4 class="font-medium mb-2">ข้อมูลการเช่า</h4>
                            <p>วันที่เริ่มเช่า: <span id="rent_start_date">-</span></p>
                            <p>ค่าเช่าห้อง: <span id="room_price">-</span> บาท</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-medium mb-2">ออกบิลประจำเดือน</h4>
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="billing_month" value="เดือนที่เรียกเก็บ" />
                                    <select id="billing_month" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="1">มกราคม</option>
                                        <option value="2">กุมภาพันธ์</option>
                                        <option value="3">มีนาคม</option>
                                        <option value="4">เมษายน</option>
                                        <option value="5">พฤษภาคม</option>
                                        <option value="6">มิถุนายน</option>
                                        <option value="7">กรกฎาคม</option>
                                        <option value="8">สิงหาคม</option>
                                        <option value="9">กันยายน</option>
                                        <option value="10">ตุลาคม</option>
                                        <option value="11">พฤศจิกายน</option>
                                        <option value="12">ธันวาคม</option>
                                    </select>
                                </div>
                                <div>
                                    <x-label for="billing_year" value="ปี" />
                                    <x-input id="billing_year" type="number" class="block mt-1 w-full" value="2025" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="water_units" value="หน่วยน้ำที่ใช้" />
                                    <x-input id="water_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" />
                                </div>
                                <div>
                                    <x-label for="electricity_units" value="หน่วยไฟที่ใช้" />
                                    <x-input id="electricity_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-button>
                                    ออกบิล
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

                    // In a real application, you would fetch tenant details for this room
                    // For now, we'll just show placeholder data
                    document.getElementById('tenant_name').textContent = 'ผู้เช่าห้อง ' + roomNumber;
                    document.getElementById('tenant_phone').textContent = '08x-xxx-xxxx';
                    document.getElementById('rent_start_date').textContent = '01/01/2025';
                    document.getElementById('room_price').textContent = '4,500';

                    // Set current month and year
                    const now = new Date();
                    document.getElementById('billing_month').value = now.getMonth() + 1;
                    document.getElementById('billing_year').value = now.getFullYear();
                }
            </script>
        </div>
    </div>
</x-admin-layout>
