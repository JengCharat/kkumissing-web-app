<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('แดชบอร์ดผู้เช่า') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Room Information Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">ข้อมูลห้องพัก</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">รหัสห้อง</p>
                        <p class="text-lg font-semibold">{{$userId}}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">หมายเลขห้อง</p>
                        <p class="text-lg font-semibold">{{$rooms->roomNumber}}</p>
                    </div>
                </div>
            </div>

            <!-- Billing Summary Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">สรุปค่าใช้จ่าย</h2>

                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-blue-800">Total Amount Due</h3>
                        <p class="text-2xl font-bold text-blue-800">฿ {{ number_format($rooms->water_price + $rooms->electricity_price, 2) }}</p>
                        <form method = "post" action="/dashboard/upload_slip" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="roomID" value="{{$rooms->roomID}}">
                            <input type="file" name = "slip_image">
                            <button>PAY</button>
                            <br>
                            {{$status}}
                        </form>

                        <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" onclick="printReceipt({{$bills_id}})">พิมพ์ใบเสร็จ</button>
                        <script>
                            function printReceipt(billId) {
                                // Open a new window or tab with the receipt for printing
                                window.open(`/dashboard/print-receipt/{{$bills_id}}`, '_blank');
                            }
                        </script>
                    </div>

                    @if($bill && $bill->status != 'ชำระแล้ว')
                        <p class="text-sm text-blue-600">กรุณาชำระเงินก่อนวันครบกำหนดเพื่อหลีกเลี่ยงค่าปรับล่าช้า</p>
                    @endif
                </div>

                @if($bill && $bill->status != 'ชำระแล้ว')
                    <!-- Additional Fees Card -->
                    <div class="border border-gray-200 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">ค่าใช้จ่ายเพิ่มเติม</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">ค่าเช่าห้อง</span>
                                <p class="font-bold text-blue-600">฿ {{ number_format($bill->room_rate, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">ค่าเสียหาย</span>
                                <p class="font-bold text-blue-600">฿ {{ number_format($bill->damage_fee, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="text-gray-600">ค่าปรับล่าช้า</span>
                                <p class="font-bold text-blue-600">฿ {{ number_format($bill->overdue_fee, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Water Charges -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">ค่าน้ำ</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">มิเตอร์ครั้งก่อน</span>
                                    <span class="font-medium">{{$meter_detail->water_meter_start}} units</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">มิเตอร์ครั้งล่าสุด</span>
                                    <span class="font-medium">{{$meter_detail->water_meter_end}} units</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ปริมาณที่ใช้</span>
                                    <span class="font-medium">{{$meter_detail->water_meter_end - $meter_detail->water_meter_start}} units</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="font-semibold">ค่าน้ำรวม</span>
                                    <span class="font-bold text-blue-600">฿ {{ number_format($bill->water_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Electricity Charges -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">ค่าไฟฟ้า</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">มิเตอร์ครั้งก่อน</span>
                                    <span class="font-medium">{{$meter_detail->electricity_meter_start}} units</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">มิเตอร์ครั้งล่าสุด</span>
                                    <span class="font-medium">{{$meter_detail->electricity_meter_end}} units</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ปริมาณที่ใช้</span>
                                    <span class="font-medium">{{$meter_detail->electricity_meter_end - $meter_detail->electricity_meter_start}} units</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="font-semibold">ค่าไฟฟ้ารวม</span>
                                    <span class="font-bold text-blue-600">฿ {{ number_format($bill->electricity_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($bill && $bill->status == 'ชำระแล้ว')
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <p class="text-lg text-green-700">ไม่มีค่าใช้จ่ายที่ต้องชำระในขณะนี้</p>
                        <p class="text-sm text-green-600">ระบบจะแสดงค่าใช้จ่ายเมื่อมีการออกบิลใหม่</p>
                    </div>
                @else
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <p class="text-lg text-yellow-700">ไม่พบข้อมูลบิล</p>
                        <p class="text-sm text-yellow-600">กรุณาติดต่อผู้ดูแลระบบเพื่อสร้างบิลใหม่</p>
                    </div>
                @endif
            </div>

            <!-- Meter Information Card -->
            {{-- <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Meter Information</h2>
                <p class="text-gray-600 mb-4">Meter ID: {{$meter_reading->meterID}}</p>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500 mb-2">Reading Period</p>
                    <p class="text-md">The current billing is based on meter readings taken for this period.</p>
                </div>
            </div> --}}
        </div>
    </div>
</x-app-layout>
