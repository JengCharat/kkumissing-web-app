<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดการลูกค้ารายเดือน') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="{{ route('admin.monthly-tenants') }}" class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">ลูกค้ารายเดือน</a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('admin.daily-tenants') }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">ลูกค้ารายวัน</a>
                    </li>
                </ul>
            </div>

            <!-- Monthly Tenants -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">รายชื่อลูกค้ารายเดือน</h3>
                        <div class="flex space-x-2">
                            <div class="relative">
                                <input type="text" placeholder="ค้นหา..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-button onclick="showAddTenantForm()">
                                + เพิ่มลูกค้าใหม่
                            </x-button>
                        </div>
                    </div>

                    <!-- Tenants Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ห้อง</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ชื่อ-นามสกุล</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">เบอร์โทรศัพท์</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">วันที่เข้าพัก</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ค่าเช่า/เดือน</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">สถานะ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @if(count($tenants) > 0)
                                    @foreach($tenants as $tenant)
                                        <tr data-tenant-id="{{ $tenant->tenantID }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $tenant->bookings && $tenant->bookings->room ? $tenant->bookings->room->roomNumber : 'ไม่ระบุ' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $tenant->tenantName }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $tenant->telNumber }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $tenant->bookings && $tenant->bookings->check_in ? date('d/m/Y', strtotime($tenant->bookings->check_in)) : 'ไม่ระบุ' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $tenant->bookings && $tenant->bookings->room ? $tenant->bookings->room->month_rate ?? 'ไม่ระบุ' : 'ไม่ระบุ' }} บาท
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($tenant->bookings && $tenant->bookings->room)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        อยู่ระหว่างเช่า
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        ไม่ได้เช่าห้อง
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="showTenantDetails({{ $tenant->tenantID }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">ดูรายละเอียด</button>
                                                <button onclick="showEditTenantForm({{ $tenant->tenantID }}, '{{ $tenant->tenantName }}', '{{ $tenant->telNumber }}')" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2">แก้ไข</button>
                                                <button onclick="confirmDeleteTenant({{ $tenant->tenantID }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">ย้ายออก</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            ไม่พบข้อมูลลูกค้ารายเดือน
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (if needed) -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                แสดง <span class="font-medium">{{ count($tenants) }}</span> รายการ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Tenant Modal -->
    <div id="add_tenant_modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">เพิ่มลูกค้ารายเดือนใหม่</h3>
                <button onclick="hideAddTenantForm()" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="add_tenant_form" method="POST" action="{{ route('admin.create-monthly-tenant') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tenant_type" value="monthly">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="room_id" value="เลือกห้องว่าง" />
                        <select id="room_id" name="roomNumber" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="">-- เลือกห้อง --</option>
                            <!-- Available rooms will be loaded here dynamically -->
                        </select>
                    </div>
                    <div>
                        <x-label for="due_date" value="วันครบกำหนด" />
                        <x-input id="due_date" name="due_date" type="date" class="block mt-1 w-full" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="tenant_name" value="ชื่อผู้เช่า" />
                        <x-input id="tenant_name" name="tenantName" type="text" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-label for="tel_number" value="เบอร์โทรศัพท์" />
                        <x-input id="tel_number" name="tenantTel" type="text" class="block mt-1 w-full" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="deposit" value="เงินมัดจำ (บาท)" />
                        <x-input id="deposit" name="deposit" type="number" step="0.01" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-label for="contract_file" value="อัพโหลดสัญญา" />
                        <input id="contract_file" name="img" type="file" class="block mt-1 w-full" required />
                    </div>
                </div>

                <div class="mt-4 flex space-x-2 justify-end">
                    <x-button type="button" class="bg-gray-500" onclick="hideAddTenantForm()">
                        ยกเลิก
                    </x-button>
                    <x-button type="submit">
                        บันทึก
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tenant Modal -->
    <div id="edit_tenant_modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">แก้ไขข้อมูลลูกค้า</h3>
                <button onclick="hideEditTenantForm()" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="edit_tenant_form" method="POST" action="{{ route('admin.update-monthly-tenant') }}">
                @csrf
                <input type="hidden" id="edit_tenant_id" name="tenantID">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="edit_tenant_name" value="ชื่อผู้เช่า" />
                        <x-input id="edit_tenant_name" name="tenantName" type="text" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-label for="edit_tel_number" value="เบอร์โทรศัพท์" />
                        <x-input id="edit_tel_number" name="tenantTel" type="text" class="block mt-1 w-full" required />
                    </div>
                </div>

                <div class="mt-4 flex space-x-2 justify-end">
                    <x-button type="button" class="bg-gray-500" onclick="hideEditTenantForm()">
                        ยกเลิก
                    </x-button>
                    <x-button type="submit">
                        บันทึก
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tenant Details Modal -->
    <div id="tenant_details_modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">รายละเอียดลูกค้า</h3>
                <button onclick="hideTenantDetails()" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="tenant_details_content" class="space-y-4">
                <!-- Tenant details will be loaded here dynamically -->
                <div class="flex justify-between">
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ชื่อผู้เช่า</p>
                        <p id="detail_tenant_name" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">เบอร์โทรศัพท์</p>
                        <p id="detail_tenant_tel" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ห้อง</p>
                        <p id="detail_room_number" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ค่าเช่า/เดือน</p>
                        <p id="detail_room_rate" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">วันที่เข้าพัก</p>
                        <p id="detail_check_in" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">วันครบกำหนด</p>
                        <p id="detail_due_date" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">เงินมัดจำ</p>
                        <p id="detail_deposit" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div class="w-1/2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">สถานะ</p>
                        <p id="detail_status" class="text-base text-gray-900 dark:text-gray-100">-</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-button type="button" onclick="hideTenantDetails()">
                    ปิด
                </x-button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete_confirmation_modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">ยืนยันการย้ายออก</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">คุณต้องการให้ลูกค้าย้ายออกใช่หรือไม่? การดำเนินการนี้จะทำให้ห้องว่างและลบข้อมูลลูกค้า</p>
            </div>

            <div class="flex justify-end space-x-2">
                <x-button type="button" class="bg-gray-500" onclick="hideDeleteConfirmation()">
                    ยกเลิก
                </x-button>
                <x-button type="button" class="bg-red-600" id="confirm_delete_button">
                    ยืนยัน
                </x-button>
            </div>
        </div>
    </div>

    <script>
        // Load available rooms when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadAvailableRooms();
        });

        function loadAvailableRooms() {
            fetch('/admin/get-available-rooms')
                .then(response => response.json())
                .then(data => {
                    const roomSelect = document.getElementById('room_id');
                    roomSelect.innerHTML = '<option value="">-- เลือกห้อง --</option>';

                    if (data.rooms && data.rooms.length > 0) {
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.roomNumber;
                            option.textContent = `${room.roomNumber} - ค่าเช่า ${room.month_rate} บาท/เดือน`;
                            roomSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "ไม่มีห้องว่าง";
                        option.disabled = true;
                        roomSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading available rooms:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลห้องว่าง');
                });
        }

        function showAddTenantForm() {
            document.getElementById('add_tenant_modal').classList.remove('hidden');
            // Set default due date (1 month from today)
            const today = new Date();
            const nextMonth = new Date(today);
            nextMonth.setMonth(today.getMonth() + 1);

            document.getElementById('due_date').value = nextMonth.toISOString().split('T')[0];
        }

        function hideAddTenantForm() {
            document.getElementById('add_tenant_modal').classList.add('hidden');
            document.getElementById('add_tenant_form').reset();
        }

        function showEditTenantForm(tenantId, tenantName, tenantTel) {
            document.getElementById('edit_tenant_id').value = tenantId;
            document.getElementById('edit_tenant_name').value = tenantName;
            document.getElementById('edit_tel_number').value = tenantTel;
            document.getElementById('edit_tenant_modal').classList.remove('hidden');
        }

        function hideEditTenantForm() {
            document.getElementById('edit_tenant_modal').classList.add('hidden');
            document.getElementById('edit_tenant_form').reset();
        }

        function showTenantDetails(tenantId) {
            document.getElementById('tenant_details_modal').classList.remove('hidden');

            // Fetch tenant details from the server
            fetch(`/admin/get-tenant-details/${tenantId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching tenant details:', data.error);
                        return;
                    }

                    const tenant = data.tenant;
                    const booking = data.booking;
                    const room = data.room;

                    // Set tenant details
                    document.getElementById('detail_tenant_name').textContent = tenant.tenantName || 'ไม่ระบุ';
                    document.getElementById('detail_tenant_tel').textContent = tenant.telNumber || 'ไม่ระบุ';

                    // Set room details
                    document.getElementById('detail_room_number').textContent = room ? room.roomNumber : 'ไม่ระบุ';
                    document.getElementById('detail_room_rate').textContent = room ? (room.month_rate + ' บาท') : 'ไม่ระบุ';

                    // Set booking details
                    document.getElementById('detail_check_in').textContent = booking && booking.check_in ?
                        new Date(booking.check_in).toLocaleDateString('th-TH') : 'ไม่ระบุ';
                    document.getElementById('detail_due_date').textContent = booking && booking.due_date ?
                        new Date(booking.due_date).toLocaleDateString('th-TH') : 'ไม่ระบุ';

                    // Set deposit - this is the key change we're making
                    document.getElementById('detail_deposit').textContent = booking && booking.deposit ?
                        booking.deposit + ' บาท' : 'ไม่ระบุ';

                    // Set status
                    document.getElementById('detail_status').textContent = room ? 'อยู่ระหว่างเช่า' : 'ไม่ได้เช่าห้อง';
                })
                .catch(error => {
                    console.error('Error fetching tenant details:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลูกค้า');
                });
        }

        function hideTenantDetails() {
            document.getElementById('tenant_details_modal').classList.add('hidden');
        }

        function confirmDeleteTenant(tenantId) {
            document.getElementById('delete_confirmation_modal').classList.remove('hidden');

            // Set up the delete button to perform the actual delete
            document.getElementById('confirm_delete_button').onclick = function() {
                deleteTenant(tenantId);
            };
        }

        function hideDeleteConfirmation() {
            document.getElementById('delete_confirmation_modal').classList.add('hidden');
        }

        function deleteTenant(tenantId) {
            // Send delete request to the server
            fetch(`/admin/delete-monthly-tenant/${tenantId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Reload the page to show updated tenant list
                    window.location.reload();
                } else {
                    throw new Error('Failed to delete tenant');
                }
            })
            .catch(error => {
                console.error('Error deleting tenant:', error);
                alert('เกิดข้อผิดพลาดในการลบข้อมูลลูกค้า');
                hideDeleteConfirmation();
            });
        }
    </script>
</x-admin-layout>
