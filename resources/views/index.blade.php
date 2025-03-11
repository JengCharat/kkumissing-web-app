<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apartment</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/script.js') }}"> </script>
    <!-- Include Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .room-button {
            padding: 0.5rem;
            text-align: center;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .available {
            background-color: #10b981;
            color: white;
        }
        .not-available {
            background-color: #ef4444;
            color: white;
        }
        .monthly-tenant {
            background-color: #3b82f6;
            color: white;
        }
        .available:hover {
            background-color: #059669;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">จองห้องสำเร็จ!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <p class="mt-2">หากมีข้อสงสัยกรุณาติดต่อเจ้าของหอพักที่เบอร์: <strong>088-670-7555</strong></p>
            </div>
            @endif

            <!-- Room Display -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">วราภรณ์ แมนชั่น [จองรายเดือน โทร: 088-670-7555]</h3>
                        <div class="flex space-x-2">
                            <button onclick="openHistoryPage()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                ประวัติการจอง
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                ไปที่แดชบอร์ด
                            </a>
                        </div>
                    </div>

<script>
    // ฟังก์ชันตรวจสอบความถูกต้องของฟอร์มกรองวันที่
    function validateDateFilter() {
        const checkinDate = document.getElementById('filter_checkin').value;
        const checkoutDate = document.getElementById('filter_checkout').value;

        // ถ้าไม่ได้กรอกทั้งสองช่อง ให้แจ้งเตือน
        if (!checkinDate || !checkoutDate) {
            alert('กรุณากรอกวันที่เช็คอินและเช็คเอาท์ให้ครบถ้วน');
            return false;
        }

        // ตรวจสอบว่าวันที่เช็คเอาท์มากกว่าวันที่เช็คอินหรือไม่
        if (new Date(checkoutDate) <= new Date(checkinDate)) {
            alert('วันที่เช็คเอาท์ต้องมากกว่าวันที่เช็คอิน');
            return false;
        }

        return true;
    }

    function openHistoryPage() {
        window.location.href = '/history';  // เปลี่ยนเส้นทางไปยังหน้า /history
    }

    // เก็บข้อมูลว่าห้องที่เลือกเป็นห้องของผู้เช่ารายเดือนหรือไม่
    let selectedMonthlyRoom = false;

    // ฟังก์ชันคำนวณจำนวนวันและราคารวม
    function calculateDaysAndPrice() {
        const checkinDate = new Date(document.getElementById('daily_checkin').value);
        const checkoutDate = new Date(document.getElementById('daily_checkout').value);

        // ตรวจสอบว่าวันที่ถูกต้องหรือไม่
        if (isNaN(checkinDate.getTime()) || isNaN(checkoutDate.getTime())) {
            document.getElementById('days_count').textContent = '-';
            document.getElementById('total_price').textContent = '-';
            return;
        }

        // ตรวจสอบว่าวันที่เช็คเอาท์มากกว่าวันที่เช็คอินหรือไม่
        if (checkoutDate <= checkinDate) {
            document.getElementById('days_count').textContent = 'วันที่ไม่ถูกต้อง';
            document.getElementById('total_price').textContent = '-';
            return;
        }

        // คำนวณจำนวนวัน
        const diffTime = Math.abs(checkoutDate - checkinDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // แสดงจำนวนวัน
        document.getElementById('days_count').textContent = diffDays + ' วัน';

        // คำนวณราคารวม (สมมติว่าราคาต่อวันคือ 500 บาท)
        const pricePerDay = 400;
        const totalPrice = diffDays * pricePerDay;

        // แสดงราคารวม
        document.getElementById('total_price').textContent = totalPrice + ' บาท';
    }

    // ฟังก์ชันแสดง QR code
    function show_qr() {
        // แสดง QR code สำหรับทั้งฟอร์มรายวันและรายเดือน
        const dailyQR = document.getElementById('qr_img_daily');
        const monthlyQR = document.getElementById('qr_img_monthly');

        if (dailyQR) dailyQR.style.display = 'block';
        if (monthlyQR) monthlyQR.style.display = 'block';
    }

    // ฟังก์ชันเดิมที่ถูกเรียกเมื่อเลือกห้อง
    function select_this_room(roomID) {
        // เก็บค่า roomID ไว้ใน input hidden
        document.getElementById('room_ID_select2').value = roomID;

        // ตรวจสอบว่าห้องที่เลือกเป็นห้องของผู้เช่ารายเดือนหรือไม่
        const roomButton = document.querySelector(`button[onclick="select_this_room('${roomID}')"]`);
        selectedMonthlyRoom = roomButton && roomButton.hasAttribute('data-monthly');

        // แสดงหมายเลขห้องที่เลือก
        const roomNumber = roomButton ? roomButton.textContent.trim() : '';
        document.getElementById('room_ID_select').textContent = roomNumber;

        // แสดงรายละเอียดห้อง
        document.getElementById('room_details').classList.remove('hidden');
    }

    // แทนที่ฟังก์ชัน daily_form เดิม
    function daily_form() {
        // ตรวจสอบว่าได้เลือกห้องหรือไม่
        if (document.getElementById('room_ID_select2').value === '') {
            alert('กรุณาเลือกห้องก่อน');
            return;
        }

        // ตรวจสอบว่าเป็นห้องของผู้เช่ารายเดือนหรือไม่
        if (selectedMonthlyRoom) {
            alert('ห้องนี้ถูกจองโดยผู้เช่ารายเดือนแล้ว ไม่สามารถจองได้');
            return;
        }

        // ดำเนินการต่อหากไม่ใช่ห้องของผู้เช่ารายเดือน
        const roomNumber = document.getElementById('room_ID_select').textContent;
        document.getElementById('room_display_daily').textContent = roomNumber;
        document.getElementById('room_ID_select_daily').value = document.getElementById('room_ID_select2').value;

        // ซ่อนฟอร์มรายเดือน และแสดงฟอร์มรายวัน
        document.getElementById('monthly_form').style.display = 'none';
        document.getElementById('daily_form').style.display = 'block';
    }
</script>

                    <!-- Date Filter -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-md font-medium mb-2">กรองตามวันที่</h4>
                        <form action="{{ route('index') }}" method="GET" class="flex flex-wrap gap-4" id="date-filter-form" onsubmit="return validateDateFilter()">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คอิน</label>
                                <input type="date" name="checkin" id="filter_checkin" value="{{ $check_in ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คเอาท์</label>
                                <input type="date" name="checkout" id="filter_checkout" value="{{ $check_out ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">กรอง</button>
                                @if(isset($check_in) || isset($check_out))
                                    <a href="{{ route('index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">ล้าง</a>
                                @endif
                            </div>
                        </form>
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

                        <!-- Room Images Section -->
                        <div class="mt-4 mb-4">
                            <h5 class="text-sm font-medium mb-2">รูปภาพห้อง</h5>
                            <div class="grid grid-cols-3 gap-2" id="room_images">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/rooms/room_image_1.jpg') }}" alt="Room Image 1" class="object-cover rounded-md w-full h-full">
                                </div>
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/rooms/room_image_2.jpg') }}" alt="Room Image 2" class="object-cover rounded-md w-full h-full">
                                </div>
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/rooms/room_image_3.jpg') }}" alt="Room Image 3" class="object-cover rounded-md w-full h-full">
                                </div>
                            </div>
                        </div>

                        <div id="booking_schedule" class="mt-4">
                            {{-- <h5 class="text-sm font-medium mb-2">ตารางการจอง</h5>
                            <div id="booking_list" class="space-y-2">
                                <!-- Booking entries will be populated here -->
                            </div> --}}
                        </div>
                    </div>

                    <div class="mb-8">

                        <h4 class="text-md font-medium mb-2">ห้องฝั่งซ้าย</h4>
                        <div class="grid grid-cols-6 gap-2">

                        @foreach ($Lrooms->chunk(6) as $room)
                            @foreach ($room as $item)
                                @php
                                    // ตรวจสอบว่า roomID นี้อยู่ใน list ของ room_id_that_has_been_taken
                                    $isBooked = in_array($item->roomID, $room_id_that_has_been_taken);
                                    // ตรวจสอบว่าเป็นห้องที่จองโดยผู้เช่ารายเดือนหรือไม่
                                    $isMonthlyTenant = in_array($item->roomID, $monthly_tenant_rooms ?? []);
                                @endphp

                                @if (!$isBooked)
                                    <!-- ปุ่มสำหรับห้องที่ว่างและยังไม่ถูกจอง -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button available">
                                        {{ $item->roomNumber }}
                                    </button>
                                @elseif ($isMonthlyTenant)
                                    <!-- ปุ่มสำหรับห้องที่จองโดยผู้เช่ารายเดือน -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button monthly-tenant" data-monthly="true">
                                        {{ $item->roomNumber }}
                                    </button>
                                @else
                                    <!-- ปุ่มสำหรับห้องที่ถูกจองหรือไม่ว่าง -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button not-available">
                                        {{ $item->roomNumber }}
                                    </button>
                                @endif
                            @endforeach
                        @endforeach
                            {{-- @foreach ($Lrooms->chunk(6) as $room) --}}
                            {{--     @foreach ($room as $item) --}}
                            {{--         @php --}}
                            {{--             $isBooked = false; --}}
                            {{--             if (isset($bookings) && isset($check_in) && isset($check_out)) { --}}
                            {{--                 $isBooked = isset($bookings[$item->roomID]); --}}
                            {{--             } --}}
                            {{--         @endphp --}}
                            {{----}}
                            {{--         @if ($item->status == "Available" && !$isBooked) --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="room-button available"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--         @else --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="room-button not-available"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--         @endif --}}
                            {{--     @endforeach --}}
                            {{-- @endforeach --}}
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-md font-medium mb-2">ห้องฝั่งขวา</h4>
                        <div class="grid grid-cols-6 gap-2">

                    {{-- @if ($room_id_that_has_been_taken && count($room_id_that_has_been_taken) > 0) --}}
                        @foreach ($Rrooms->chunk(6) as $room)
                            @foreach ($room as $item)
                                @php
                                    // ตรวจสอบว่า roomID นี้อยู่ใน list ของ room_id_that_has_been_taken
                                    $isBooked = in_array($item->roomID, $room_id_that_has_been_taken);
                                    // ตรวจสอบว่าเป็นห้องที่จองโดยผู้เช่ารายเดือนหรือไม่
                                    $isMonthlyTenant = in_array($item->roomID, $monthly_tenant_rooms ?? []);
                                @endphp

                                @if (!$isBooked)
                                    <!-- ปุ่มสำหรับห้องที่ว่างและยังไม่ถูกจอง -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button available">
                                        {{ $item->roomNumber }}
                                    </button>
                                @elseif ($isMonthlyTenant)
                                    <!-- ปุ่มสำหรับห้องที่จองโดยผู้เช่ารายเดือน -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button monthly-tenant" data-monthly="true">
                                        {{ $item->roomNumber }}
                                    </button>
                                @else
                                    <!-- ปุ่มสำหรับห้องที่ถูกจองหรือไม่ว่าง -->
                                    <button onclick="select_this_room('{{ $item->roomID }}')" class="room-button not-available">
                                        {{ $item->roomNumber }}
                                    </button>
                                @endif
                            @endforeach
                        @endforeach
                    {{-- @endif --}}
                            {{-- @foreach ($Rrooms->chunk(6) as $room) --}}
                            {{--     @foreach ($room as $item) --}}
                            {{--         @php --}}
                            {{--             $isBooked = false; --}}
                            {{--             if (isset($bookings) && isset($check_in) && isset($check_out)) { --}}
                            {{--                 $isBooked = isset($bookings[$item->roomID]); --}}
                            {{--             } --}}
                            {{--         @endphp --}}
                            {{----}}
                            {{--         @if ($item->status == "Available" && !$isBooked) --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="room-button available"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--         @else --}}
                            {{--         <button onclick="select_this_room('{{ $item->roomID }}')" --}}
                            {{--                 class="room-button not-available"> --}}
                            {{--             {{ $item->roomNumber }} --}}
                            {{--         </button> --}}
                            {{--         @endif --}}
                            {{--     @endforeach --}}
                            {{-- @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Booking Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex space-x-4 mb-4">
                        {{-- <button type="button" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">จองเลย</button> --}}
                        <button type="button" onclick="daily_form()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">จองเลย</button>
                        {{-- <button type="button" onclick="monthly_form()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">รายเดือน</button> --}}
                    </div>

                    <div id="monthly_form" class="monthly_form bg-gray-50 p-6 rounded-lg" style="display:none">
                        <h2 class="text-xl font-semibold mb-2">การจองรายเดือน</h2>
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <p class="text-md font-medium text-gray-700">ห้องที่เลือก: <span id="room_display_monthly" class="font-bold text-blue-600 text-lg"></span></p>
                        </div>

                        <form method="POST" action="/hire" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="roomNumber" id="room_ID_select_monthly" value="">
                            <input type="hidden" name="tenant_type" value="monthly">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">วันครบกำหนด</label>
                                <input type="date" name="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เงินมัดจำ</label>
                                <input type="text" name="deposit" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้เช่า</label>
                                <input type="text" name="tenantName" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์</label>
                                <input type="tel" name="tenantTel" class="w-full px-3 py-2 border border-gray-300 rounded-md" maxlength="10">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">การชำระเงิน</label>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="show_qr()" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">QR Code</button>
                                    <button type="button" class="px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">เงินสด</button>
                                </div>
                                <img style="display:none;" src="{{asset('images/rickroll.png')}}" alt="qr img" id="qr_img_monthly" class="mt-2 max-w-xs">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">อัพโหลดสลิป</label>
                                <input type="file" name="img" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>

                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">ยืนยันการจอง</button>
                        </form>
                    </div>

                    <div id="daily_form" class="daily_form bg-gray-50 p-6 rounded-lg" style="display:none">
                        <h1>TEL:0886707555</h1>
                        <h2 class="text-xl font-semibold mb-2">การจองรายวัน</h2>
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <p class="text-md font-medium text-gray-700">ห้องที่เลือก: <span id="room_display_daily" class="font-bold text-blue-600 text-lg"></span></p>
                        </div>
                        <form method="POST" action="{{ route('hire') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="roomNumber" id="room_ID_select_daily" value="">
                            <input type="hidden" name="tenant_type" value="daily">

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คอิน</label>
                                <input type="date" name="checkin" id="daily_checkin" value="{{ $check_in ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required onchange="calculateDaysAndPrice()">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เช็คเอาท์</label>
                                <input type="date" name="checkout" id="daily_checkout" value="{{ $check_out ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required onchange="calculateDaysAndPrice()">
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนวัน</label>
                                <div id="days_count" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">-</div>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">ราคารวม</label>
                                <div id="total_price" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">-</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้เช่า</label>
                                <input type="text" name="tenantName" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์</label>
                                <input type="tel" name="tenantTel" class="w-full px-3 py-2 border border-gray-300 rounded-md" pattern="[0-9\-\+\(\)\s]+" maxlength="10">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">การชำระเงิน</label>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="show_qr()" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">QR Code</button>
                                    {{-- <button type="button" class="px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">เงินสด</button> --}}
                                </div>
                                <img style="display:none;" src="{{asset('images/rickroll.png')}}" alt="qr img" id="qr_img_daily" class="mt-2 max-w-xs">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">อัพโหลดสลิป</label>
                                <input type="file" name="img" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">ยืนยันการจอง</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
