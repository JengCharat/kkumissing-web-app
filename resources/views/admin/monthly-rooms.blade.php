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
                                    <x-input id="room_rate" name="room_rate" type="number" step="0.01" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="water_rate" value="ค่าน้ำต่อหน่วย" />
                                    <x-input id="water_rate" name="water_rate" type="number" step="0.01" class="block mt-1 w-full" />
                                </div>
                                <div>
                                    <x-label for="water_units" value="หน่วยน้ำที่ใช้" />
                                    <x-input id="water_units" name="water_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="water_total" value="รวมค่าน้ำ" />
                                    <x-input id="water_total" name="water_price" type="number" step="0.01" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="electricity_rate" value="ค่าไฟต่อหน่วย" />
                                    <x-input id="electricity_rate" name="electricity_rate" type="number" step="0.01" class="block mt-1 w-full" />
                                </div>
                                <div>
                                    <x-label for="electricity_units" value="หน่วยไฟที่ใช้" />
                                    <x-input id="electricity_units" name="electricity_units" type="number" class="block mt-1 w-full" placeholder="จำนวนหน่วย" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="electricity_total" value="รวมค่าไฟ" />
                                    <x-input id="electricity_total" name="electricity_price" type="number" step="0.01" class="block mt-1 w-full" readonly />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-label for="damage_fee" value="ค่าเสียหาย" />
                                    <x-input id="damage_fee" name="damage_fee" type="number" step="0.01" class="block mt-1 w-full" value="0" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="overdue_fee" value="ค่าปรับล่าช้า" />
                                    <x-input id="overdue_fee" name="overdue_fee" type="number" step="0.01" class="block mt-1 w-full" value="0" onchange="calculateTotal()" />
                                </div>
                                <div>
                                    <x-label for="total_price" value="ยอดรวมทั้งหมด" />
                                    <x-input id="total_price" name="total_price" type="number" step="0.01" class="block mt-1 w-full" readonly />
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
                                <!-- Bills will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                function select_this_room(roomID, roomNumber) {
                    // Show room details section
                    document.getElementById('room_details').style.display = 'block';
                    document.getElementById('selected_room_number').textContent = roomNumber;

                    // Store roomID in a hidden field for form submission
                    if (!document.getElementById('room_id')) {
                        const hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.id = 'room_id';
                        hiddenField.name = 'roomID';
                        hiddenField.value = roomID;
                        document.getElementById('bill_form').appendChild(hiddenField);
                    } else {
                        document.getElementById('room_id').value = roomID;
                    }

                    // Fetch tenant details for this room using AJAX
                    fetch(`/admin/get-room-tenant/${roomID}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.tenant) {
                                document.getElementById('tenant_name').textContent = data.tenant.tenantName;
                                document.getElementById('tenant_phone').textContent = data.tenant.telNumber;

                                // Store tenantID in a hidden field for form submission
                                if (!document.getElementById('tenant_id')) {
                                    const hiddenField = document.createElement('input');
                                    hiddenField.type = 'hidden';
                                    hiddenField.id = 'tenant_id';
                                    hiddenField.name = 'tenantID';
                                    hiddenField.value = data.tenant.tenantID;
                                    document.getElementById('bill_form').appendChild(hiddenField);
                                } else {
                                    document.getElementById('tenant_id').value = data.tenant.tenantID;
                                }
                            } else {
                                document.getElementById('tenant_name').textContent = 'ไม่พบข้อมูลผู้เช่า';
                                document.getElementById('tenant_phone').textContent = '-';
                            }

                            if (data.booking) {
                                document.getElementById('rent_start_date').textContent = new Date(data.booking.check_in).toLocaleDateString('th-TH');
                            } else {
                                document.getElementById('rent_start_date').textContent = '-';
                            }

                            if (data.room) {
                                document.getElementById('room_price').textContent = data.room.month_rate.toLocaleString();
                            } else {
                                document.getElementById('room_price').textContent = '-';
                            }

                            // Load existing bills for this room
                            loadBills(roomID);
                        })
                        .catch(error => {
                            console.error('Error fetching room details:', error);
                            alert('เกิดข้อผิดพลาดในการดึงข้อมูลห้อง');
                        });

                    // Set current month and year
                    const now = new Date();
                    document.getElementById('billing_month').value = now.getMonth() + 1;
                    document.getElementById('billing_year').value = now.getFullYear();
                }

                function loadBills(roomID) {
                    fetch(`/admin/get-room-bills/${roomID}`)
                        .then(response => response.json())
                        .then(data => {
                            const billsTable = document.getElementById('bills_table_body');
                            billsTable.innerHTML = '';

                            if (data.bills && data.bills.length > 0) {
                                data.bills.forEach(bill => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td class="px-4 py-2 border">${bill.BillNo}</td>
                                        <td class="px-4 py-2 border">${new Date(bill.BillDate).toLocaleDateString('th-TH')}</td>
                                        <td class="px-4 py-2 border">${bill.water_price ? bill.water_price.toLocaleString() : '0'}</td>
                                        <td class="px-4 py-2 border">${bill.electricity_price ? bill.electricity_price.toLocaleString() : '0'}</td>
                                        <td class="px-4 py-2 border">${bill.damage_fee ? bill.damage_fee.toLocaleString() : '0'}</td>
                                        <td class="px-4 py-2 border">${bill.overdue_fee ? bill.overdue_fee.toLocaleString() : '0'}</td>
                                        <td class="px-4 py-2 border">${bill.total_price ? bill.total_price.toLocaleString() : '0'}</td>
                                        <td class="px-4 py-2 border">
                                            <button onclick="editBill(${bill.BillNo})" class="text-blue-500 hover:text-blue-700 mr-2">แก้ไข</button>
                                            <button onclick="deleteBill(${bill.BillNo})" class="text-red-500 hover:text-red-700">ลบ</button>
                                        </td>
                                    `;
                                    billsTable.appendChild(row);
                                });
                                document.getElementById('bills_table').style.display = 'table';
                                document.getElementById('no_bills_message').style.display = 'none';
                            } else {
                                document.getElementById('bills_table').style.display = 'none';
                                document.getElementById('no_bills_message').style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching bills:', error);
                            alert('เกิดข้อผิดพลาดในการดึงข้อมูลบิล');
                        });
                }

                function calculateTotal() {
                    const waterUnits = parseFloat(document.getElementById('water_units').value) || 0;
                    const electricityUnits = parseFloat(document.getElementById('electricity_units').value) || 0;
                    const waterRate = parseFloat(document.getElementById('water_rate').value) || 0;
                    const electricityRate = parseFloat(document.getElementById('electricity_rate').value) || 0;
                    const roomRate = parseFloat(document.getElementById('room_rate').value) || 0;
                    const damageFee = parseFloat(document.getElementById('damage_fee').value) || 0;
                    const overdueFee = parseFloat(document.getElementById('overdue_fee').value) || 0;

                    const waterTotal = waterUnits * waterRate;
                    const electricityTotal = electricityUnits * electricityRate;
                    const total = roomRate + waterTotal + electricityTotal + damageFee + overdueFee;

                    document.getElementById('water_total').value = waterTotal.toFixed(2);
                    document.getElementById('electricity_total').value = electricityTotal.toFixed(2);
                    document.getElementById('total_price').value = total.toFixed(2);
                }

                function editBill(billId) {
                    fetch(`/admin/get-bill/${billId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.bill) {
                                // Populate form with bill data
                                const billDate = new Date(data.bill.BillDate);
                                document.getElementById('billing_month').value = billDate.getMonth() + 1;
                                document.getElementById('billing_year').value = billDate.getFullYear();
                                document.getElementById('water_units').value = data.water_units || '';
                                document.getElementById('electricity_units').value = data.electricity_units || '';
                                document.getElementById('damage_fee').value = data.bill.damage_fee || '0';
                                document.getElementById('overdue_fee').value = data.bill.overdue_fee || '0';
                                document.getElementById('water_total').value = data.bill.water_price || '0';
                                document.getElementById('electricity_total').value = data.bill.electricity_price || '0';
                                document.getElementById('total_price').value = data.bill.total_price || '0';

                                // Add bill ID to form for update
                                if (!document.getElementById('bill_id')) {
                                    const hiddenField = document.createElement('input');
                                    hiddenField.type = 'hidden';
                                    hiddenField.id = 'bill_id';
                                    hiddenField.name = 'billId';
                                    hiddenField.value = billId;
                                    document.getElementById('bill_form').appendChild(hiddenField);
                                } else {
                                    document.getElementById('bill_id').value = billId;
                                }

                                // Change button text
                                document.getElementById('submit_button').textContent = 'อัพเดทบิล';

                                // Scroll to form
                                document.getElementById('bill_form').scrollIntoView({ behavior: 'smooth' });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching bill details:', error);
                            alert('เกิดข้อผิดพลาดในการดึงข้อมูลบิล');
                        });
                }

                function deleteBill(billId) {
                    if (confirm('คุณต้องการลบบิลนี้ใช่หรือไม่?')) {
                        fetch(`/admin/delete-bill/${billId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('ลบบิลเรียบร้อยแล้ว');
                                // Reload bills
                                loadBills(document.getElementById('room_id').value);
                            } else {
                                alert('เกิดข้อผิดพลาดในการลบบิล: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting bill:', error);
                            alert('เกิดข้อผิดพลาดในการลบบิล');
                        });
                    }
                }

                function resetForm() {
                    document.getElementById('bill_form').reset();
                    if (document.getElementById('bill_id')) {
                        document.getElementById('bill_id').remove();
                    }
                    document.getElementById('submit_button').textContent = 'บันทึก';

                    // Set current month and year
                    const now = new Date();
                    document.getElementById('billing_month').value = now.getMonth() + 1;
                    document.getElementById('billing_year').value = now.getFullYear();
                }
            </script>
        </div>
    </div>
</x-admin-layout>
