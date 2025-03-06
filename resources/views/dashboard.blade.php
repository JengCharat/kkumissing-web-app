<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Room Information Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Room Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Room ID</p>
                        <p class="text-lg font-semibold">{{$userId}}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Room Number</p>
                        <p class="text-lg font-semibold">{{$rooms->roomNumber}}</p>
                    </div>
                </div>
            </div>

            <!-- Billing Summary Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Billing Summary</h2>

                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-blue-800">Total Amount Due</h3>
                        <p class="text-2xl font-bold text-blue-800">฿ {{ $bill ? number_format($bill->water_price + $bill->electricity_price + $bill->room_rate, 2) : '0.00' }}</p>
                        <form method = "post" action="/dashboard/upload_slip" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="roomID" value="{{$rooms->roomID}}">
                            <input type="file" name = "slip_image">
                            <button>PAY</button>
                            <br>
                            @if(session('status'))
                                {{ session('status') }}
                            @endif
                        </form>
                    </div>
                    <p class="text-sm text-blue-600">Please make your payment before the due date to avoid late fees.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Water Charges -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Water Charges</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Previous Reading</span>
                                <span class="font-medium">{{$meter_detail->water_meter_start}} units</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Reading</span>
                                <span class="font-medium">{{$meter_detail->water_meter_end}} units</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Usage</span>
                                <span class="font-medium">{{$meter_detail->water_meter_end - $meter_detail->water_meter_start}} units</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="font-semibold">Total Water Charges</span>
                                <span class="font-bold text-blue-600">฿ {{ $bill ? number_format($bill->water_price, 2) : '0.00' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Electricity Charges -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Electricity Charges</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Previous Reading</span>
                                <span class="font-medium">{{$meter_detail->electricity_meter_start}} units</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Reading</span>
                                <span class="font-medium">{{$meter_detail->electricity_meter_end}} units</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Usage</span>
                                <span class="font-medium">{{$meter_detail->electricity_meter_end - $meter_detail->electricity_meter_start}} units</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="font-semibold">Total Electricity Charges</span>
                                <span class="font-bold text-blue-600">฿ {{ $bill ? number_format($bill->electricity_price, 2) : '0.00' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
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
