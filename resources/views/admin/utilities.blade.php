<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ค่าน้ำ-ไฟ') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Unit Prices -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">อัตราค่าบริการ</h3>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="/admin/update-unit-prices">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="unit_price_water" value="ค่าน้ำ (บาท/หน่วย)" />
                                <x-input id="unit_price_water" type="number" step="0.01" name="unit_price_water" value="{{ $unit_price_water ?? 18 }}" class="block mt-1 w-full" required />
                            </div>
                            <div>
                                <x-label for="unit_price_electricity" value="ค่าไฟ (บาท/หน่วย)" />
                                <x-input id="unit_price_electricity" type="number" step="0.01" name="unit_price_electricity" value="{{ $unit_price_electricity ?? 8 }}" class="block mt-1 w-full" required />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-button>
                                บันทึกการเปลี่ยนแปลง
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
