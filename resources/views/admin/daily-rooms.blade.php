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
                            <x-button type="button" onclick="showBookingForm()">
                                + เพิ่มการจอง
                            </x-button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-600">ห้องที่เลือก:</h4>
                        <h1 id="room_ID_select" class="text-2xl font-bold text-blue-600"></h1>
                    </div>
                    <input type="hidden" name="roomNumber" id="room_ID_select2" value="">

                    <!-- Room Details Section -->
                    <div id="room_details" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                        <h4 class="text-md font-medium mb-2">รายละเอียดห้อง</h4>
                        <p class="mb-2">ประเภท: ห้องแอร์พร้อมเตียง ตู้เย็น และห้องน้ำ</p>

                        {{-- <div id="booking_schedule" class="mt-4">
                            <h5 class="text-sm font-medium mb-2">ตารางการจอง</h5>
                            <div id="booking_list" class="space-y-2">
                                <!-- Booking entries will be populated here -->
                            </div>
                        </div> --}}
                    </div>




                    <form action="{{ route('admin.daily-rooms') }}" method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คอิน</label>
                                <input type="date" name="check_in" value="{{ $check_in ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คเอาท์</label>
                                <input type="date" name="check_out" value="{{ $check_out ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">กรอง</button>
                                @if(isset($check_in) || isset($check_out))
                                    <a href="{{ route('admin.daily-rooms') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">ล้าง</a>
                                @endif
                            </div>
                        </form>

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">Left Rooms</h4>
                        <div class="grid grid-cols-6 gap-2">

                            {{-- @foreach ($Lrooms->chunk(6) as $room) --}}
                            {{--     @foreach ($room as $item) --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="p-2 text-center rounded {{ $item->status == 'Available' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--     @endforeach --}}
                            {{-- @endforeach --}}

                            @foreach ($Lrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    @php
                                        // ตรวจสอบว่า roomID นี้อยู่ใน list ของ room_id_that_has_been_taken
                                        try {
                                            // ตรวจสอบว่า $daily_room_id_that_has_been_taken เป็น Collection หรือไม่
                                            if (is_a($daily_room_id_that_has_been_taken, 'Illuminate\Support\Collection')) {
                                                $room_hasbeen_taken_id_array = $daily_room_id_that_has_been_taken->keys()->toArray();
                                            } else {
                                                // ถ้าเป็น array ก็ให้เป็น array ว่าง
                                                $room_hasbeen_taken_id_array = [];
                                            }
                                        } catch (Exception $e) {
                                            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้ใช้ array ว่าง
                                            $room_hasbeen_taken_id_array = [];
                                        }

                                        // ตรวจสอบว่า roomID นี้อยู่ใน list ของ monthly_room_id_that_has_been_taken
                                        try {
                                            // ตรวจสอบว่า $monthly_room_id_that_has_been_taken เป็น Collection หรือไม่
                                            if (is_a($monthly_room_id_that_has_been_taken, 'Illuminate\Support\Collection')) {
                                                $monthly_room_hasbeen_taken_id_array = $monthly_room_id_that_has_been_taken->keys()->toArray();
                                            } else {
                                                // ถ้าเป็น array ก็ให้เป็น array ว่าง
                                                $monthly_room_hasbeen_taken_id_array = [];
                                            }
                                        } catch (Exception $e) {
                                            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้ใช้ array ว่าง
                                            $monthly_room_hasbeen_taken_id_array = [];
                                        }

                                        $isBooked = in_array($item->roomID, $room_hasbeen_taken_id_array);
                                        $isMonthlyBooked = in_array($item->roomID, $monthly_room_hasbeen_taken_id_array);
                                    @endphp

                                    @if (!$isBooked && !$isMonthlyBooked)
                                        <!-- ปุ่มสำหรับห้องที่ว่างและยังไม่ถูกจอง -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')"
                                                class="p-2 text-center rounded bg-green-500 hover:bg-green-600">
                                            {{ $item->roomNumber }}
                                        </button>
                                    @elseif ($isBooked)
                                        <!-- ปุ่มสำหรับห้องที่ถูกจองหรือไม่ว่าง -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')" class="p-2 text-center rounded bg-red-500 hover:bg-red-600">
                                            {{ $item->roomNumber }}
                                            <br>
                                            tenant name
                                            <br>
                                            ({{ optional($daily_room_id_that_has_been_taken[$item->roomID]->first())->tenantName }})
                                            ({{ optional($daily_room_id_that_has_been_taken[$item->roomID]->first())->telNumber }})
                                        </button>
                                    @elseif ($isMonthlyBooked)
                                        <!-- ปุ่มสำหรับห้องที่ถูกจองรายเดือน -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')"
                                                class="p-2 text-center rounded bg-blue-500 hover:bg-blue-600">
                                            {{ $item->roomNumber }}
                                            <h1>ห้องนี้ถูกจองรายเดือนแล้ว</h1>
                                        </button>
                                    @endif
                                @endforeach
                            @endforeach
                            {{-- @foreach ($Lrooms->chunk(6) as $room) --}}
                            {{--     @foreach ($room as $item) --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="p-2 text-center rounded {{ $item->status == 'Available' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--     @endforeach --}}
                            {{-- @endforeach --}}
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">Right Rooms</h4>
                        <div class="grid grid-cols-6 gap-2">
                            @foreach ($Rrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    @php
                                        // ตรวจสอบว่า roomID นี้อยู่ใน list ของ room_id_that_has_been_taken
                                        try {
                                            // ตรวจสอบว่า $daily_room_id_that_has_been_taken เป็น Collection หรือไม่
                                            if (is_a($daily_room_id_that_has_been_taken, 'Illuminate\Support\Collection')) {
                                                $room_hasbeen_taken_id_array = $daily_room_id_that_has_been_taken->keys()->toArray();
                                            } else {
                                                // ถ้าเป็น array ก็ให้เป็น array ว่าง
                                                $room_hasbeen_taken_id_array = [];
                                            }
                                        } catch (Exception $e) {
                                            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้ใช้ array ว่าง
                                            $room_hasbeen_taken_id_array = [];
                                        }

                                        // ตรวจสอบว่า roomID นี้อยู่ใน list ของ monthly_room_id_that_has_been_taken
                                        try {
                                            // ตรวจสอบว่า $monthly_room_id_that_has_been_taken เป็น Collection หรือไม่
                                            if (is_a($monthly_room_id_that_has_been_taken, 'Illuminate\Support\Collection')) {
                                                $monthly_room_hasbeen_taken_id_array = $monthly_room_id_that_has_been_taken->keys()->toArray();
                                            } else {
                                                // ถ้าเป็น array ก็ให้เป็น array ว่าง
                                                $monthly_room_hasbeen_taken_id_array = [];
                                            }
                                        } catch (Exception $e) {
                                            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้ใช้ array ว่าง
                                            $monthly_room_hasbeen_taken_id_array = [];
                                        }

                                        $isBooked = in_array($item->roomID, $room_hasbeen_taken_id_array);
                                        $isMonthlyBooked = in_array($item->roomID, $monthly_room_hasbeen_taken_id_array);
                                    @endphp

                                    @if (!$isBooked && !$isMonthlyBooked)
                                        <!-- ปุ่มสำหรับห้องที่ว่างและยังไม่ถูกจอง -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')"
                                                class="p-2 text-center rounded bg-green-500 hover:bg-green-600">
                                            {{ $item->roomNumber }}
                                        </button>
                                    @elseif ($isBooked)
                                        <!-- ปุ่มสำหรับห้องที่ถูกจองหรือไม่ว่าง -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')" class="p-2 text-center rounded bg-red-500 hover:bg-red-600">
                                            {{ $item->roomNumber }}
                                            <br>
                                            tenant name
                                            <br>
                                            ({{ optional($daily_room_id_that_has_been_taken[$item->roomID]->first())->tenantName }})
                                            ({{ optional($daily_room_id_that_has_been_taken[$item->roomID]->first())->telNumber }})
                                        </button>
                                    @elseif ($isMonthlyBooked)
                                        <!-- ปุ่มสำหรับห้องที่ถูกจองรายเดือน -->
                                        <button onclick="select_this_room('{{ $item->roomID }}')"
                                                class="p-2 text-center rounded bg-blue-500 hover:bg-blue-600">
                                            {{ $item->roomNumber }}
                                            <h1>ห้องนี้ถูกจองรายเดือนแล้ว</h1>
                                        </button>
                                    @endif
                                @endforeach
                            @endforeach
                            {{-- @foreach ($Rrooms->chunk(6) as $room)
                                @foreach ($room as $item)
                                    <button onclick="select_this_room('{{ $item->roomID }}')"
                                            class="p-2 text-center rounded {{ $item->status == 'Available' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white">
                                        {{ $item->roomNumber }}
                                    </button>
                                @endforeach
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form (Initially Hidden) -->
            <div id="booking_form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="display: none;">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">จองห้อง <span id="selected_room_number"></span></h3>

                    <div class="mt-4">
                        <h4 class="font-medium mb-2">ข้อมูลการจอง</h4>
                        <form method="POST" action="{{ route('hire') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="roomNumber" id="room_ID_select_daily" value="">
                            <input type="hidden" name="tenant_type" value="daily">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="tenantName" value="ชื่อผู้เข้าพัก" />
                                    <x-input id="tenantName" name="tenantName" type="text" class="block mt-1 w-full" placeholder="ชื่อ-นามสกุล" required />
                                </div>
                                <div>
                                    <x-label for="tenantTel" value="เบอร์โทรศัพท์" />
                                    <x-input id="tenantTel" name="tenantTel" type="text" class="block mt-1 w-full" placeholder="0xx-xxx-xxxx" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-label for="checkin" value="วันที่เข้าพัก" />
                                <x-input id="checkin" name="checkin" type="date" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="checkout" value="วันที่ออก" />
                                <x-input id="checkout" name="checkout" type="date" class="block mt-1 w-full" required />
                            </div>
                            </div>

                            {{-- <div>
                                <x-label for="deposit" value="เงินมัดจำ" />
                                <x-input id="deposit" name="deposit" type="number" min="0" class="block mt-1 w-full" value="0" required />
                            </div> --}}

                            {{-- <div>
                                <x-label for="deposit" value="เงินมัดจำ" />
                                <x-input id="deposit" name="deposit" type="number" min="0" class="block mt-1 w-full" value="0" required />
                            </div> --}}

                            <div>
                                <x-label value="การชำระเงิน" />
                                <div class="flex space-x-2 mt-1">
                                    <button type="button" onclick="show_qr()" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">QR Code</button>
                                    <button type="button" class="px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">เงินสด</button>
                                </div>
                                <img style="display:none;" src="{{asset('images/rickroll.png')}}" alt="qr img" id="qr_img" class="mt-2 max-w-xs">
                            </div>

                            <div>
                                <x-label for="img" value="อัพโหลดสลิป" />
                                <input type="file" name="img" id="img" class="w-full px-3 py-2 border border-gray-300 rounded-md mt-1" required>
                            </div>

                            <div class="flex justify-end mt-4">
                                <x-button type="button" class="bg-gray-500 hover:bg-gray-600 mr-2" onclick="hideBookingForm()">
                                    ยกเลิก
                                </x-button>
                                <x-button type="submit" class="bg-green-600 hover:bg-green-700">
                                    ยืนยันการจอง
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <script src="{{ asset('js/daily-rooms.js') }}"></script>
            </div>
        </div>
    </div>
</x-admin-layout>
