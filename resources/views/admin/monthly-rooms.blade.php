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
                            <x-button onclick="window.location.href='{{ route('admin.monthly-tenants') }}'">
                                + ลูกค้ารายเดือน
                            </x-button>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">Left Rooms</h4>
                        <div class="grid grid-cols-6 gap-2">
                            @foreach ($Lrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    <button onclick="{{ $item->status == 'Available' ? 'alert(\'ไม่สามารถออกบิลสำหรับห้องว่างได้\')' : 'select_this_room('.$item->roomID.', \''.$item->roomNumber.'\')' }}"
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
                                    <button onclick="{{ $item->status == 'Available' ? 'alert(\'ไม่สามารถออกบิลสำหรับห้องว่างได้\')' : 'select_this_room('.$item->roomID.', \''.$item->roomNumber.'\')' }}"
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
                        <form id="bill_form" method="POST" action="{{ route('admin.create-bill') }}" onsubmit="calculateTotal()">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="billing_month" value="เดือนที่เรียกเก็บ" />
                                    <select id="billing_month" name="billing_month" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
                                    <x-input id="billing_year" name="billing_year" type="number" class="block mt-1 w-full" value="2025" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="room_rate" value="ค่าเช่าห้อง" />
                                    <x-input id="room_rate" name="room_rate" type="number" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <x-label for="water_meter_start" value="มิเตอร์น้ำเริ่มต้น" />
                                    <x-input id="water_meter_start" name="water_meter_start" type="number" class="block mt-1 w-full" onchange="calculateWaterUnits()" />
                                </div>
                                <div>
                                    <x-label for="water_meter_end" value="มิเตอร์น้ำสิ้นสุด" />
                                    <x-input id="water_meter_end" name="water_meter_end" type="number" class="block mt-1 w-full" onchange="calculateWaterUnits()" />
                                </div>
                                <div>
                                    <x-label for="water_rate" value="ค่าน้ำต่อหน่วย" />
                                    <x-input id="water_rate" name="water_rate" type="number" class="block mt-1 w-full" readonly />
                                </div>
                                <div>
                                    <x-label for="water_total" value="รวมค่าน้ำ" />
                                    <x-input id="water_total" name="water_price" type="number" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="water_units" value="หน่วยน้ำที่ใช้" />
                                    <x-input id="water_units" name="water_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <x-label for="electricity_meter_start" value="มิเตอร์ไฟเริ่มต้น" />
                                    <x-input id="electricity_meter_start" name="electricity_meter_start" type="number" class="block mt-1 w-full" onchange="calculateElectricityUnits()" />
                                </div>
                                <div>
                                    <x-label for="electricity_meter_end" value="มิเตอร์ไฟสิ้นสุด" />
                                    <x-input id="electricity_meter_end" name="electricity_meter_end" type="number" class="block mt-1 w-full" onchange="calculateElectricityUnits()" />
                                </div>
                                <div>
                                    <x-label for="electricity_rate" value="ค่าไฟต่อหน่วย" />
                                    <x-input id="electricity_rate" name="electricity_rate" type="number" class="block mt-1 w-full" readonly />
                                </div>
                                <div>
                                    <x-label for="electricity_total" value="รวมค่าไฟ" />
                                    <x-input id="electricity_total" name="electricity_price" type="number" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="electricity_units" value="หน่วยไฟที่ใช้" />
                                    <x-input id="electricity_units" name="electricity_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="damage_fee" value="ค่าเสียหาย" />
                                    <x-input id="damage_fee" name="damage_fee" type="number" class="block mt-1 w-full" value="0" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="overdue_fee" value="ค่าปรับล่าช้า" />
                                    <x-input id="overdue_fee" name="overdue_fee" type="number" class="block mt-1 w-full" value="0" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="total_price" value="ยอดรวมทั้งหมด" />
                                    <x-input id="total_price" name="total_price" type="number" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="mt-4 flex space-x-2">
                                <x-button type="submit" id="submit_button">
                                    บันทึก
                                </x-button>
                                <x-button type="button" class="bg-gray-500" onclick="resetForm()">
                                    ล้างข้อมูล
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <!-- Bills Table -->
                    <div class="mt-8">
                        <h4 class="font-medium mb-2">ประวัติบิล</h4>
                        <p id="no_bills_message" style="display: none;">ยังไม่มีบิลสำหรับห้องนี้</p>
                        <table id="bills_table" class="min-w-full bg-white border border-gray-300" style="display: none;">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border">เลขที่บิล</th>
                                    <th class="px-4 py-2 border">วันที่</th>
                                    <th class="px-4 py-2 border">ค่าน้ำ</th>
                                    <th class="px-4 py-2 border">ค่าไฟ</th>
                                    <th class="px-4 py-2 border">ค่าเสียหาย</th>
                                    <th class="px-4 py-2 border">ค่าปรับล่าช้า</th>
                                    <th class="px-4 py-2 border">ยอดรวม</th>
                                    <th class="px-4 py-2 border">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="bills_table_body">
                                <!-- Bills will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Include the monthly-rooms.js file -->
            <script src="{{ asset('js/monthly-rooms.js') }}"></script>
        </div>
    </div>
</x-admin-layout>
