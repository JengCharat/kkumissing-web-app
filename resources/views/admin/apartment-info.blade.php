<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ข้อมูลสัญญา') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Apartment Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">ข้อมูลหอพัก</h3>
                        <x-button>
                            แก้ไขข้อมูล
                        </x-button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">ข้อมูลทั่วไป</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">ชื่อหอพัก:</span>
                                    <span class="ml-2">หอพักตัวอย่าง</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">ที่อยู่:</span>
                                    <span class="ml-2">123 ถนนตัวอย่าง ตำบล/แขวง ตัวอย่าง อำเภอ/เขต ตัวอย่าง จังหวัดตัวอย่าง 12345</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">เบอร์โทรศัพท์:</span>
                                    <span class="ml-2">02-345-6789</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">อีเมล:</span>
                                    <span class="ml-2">example@apartment.com</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">ข้อมูลเจ้าของ</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">ชื่อเจ้าของ:</span>
                                    <span class="ml-2">คุณเจ้าของ ตัวอย่าง</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">เบอร์โทรศัพท์:</span>
                                    <span class="ml-2">081-234-5678</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">อีเมล:</span>
                                    <span class="ml-2">owner@apartment.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apartment Rules -->
            {{-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">กฎระเบียบหอพัก</h3>
                        <x-button>
                            แก้ไขกฎระเบียบ
                        </x-button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium mb-2">กฎทั่วไป</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>ห้ามส่งเสียงดังรบกวนผู้อื่นหลังเวลา 22.00 น.</li>
                                <li>ห้ามนำสัตว์เลี้ยงเข้ามาในหอพัก</li>
                                <li>ห้ามสูบบุหรี่ภายในห้องพัก</li>
                                <li>ห้ามนำของผิดกฎหมายเข้ามาในหอพัก</li>
                                <li>ห้ามตอกตะปูหรือเจาะผนังโดยไม่ได้รับอนุญาต</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">การชำระเงิน</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>ชำระค่าเช่าภายในวันที่ 5 ของทุกเดือน</li>
                                <li>ค่าน้ำหน่วยละ 18 บาท</li>
                                <li>ค่าไฟหน่วยละ 8 บาท</li>
                                <li>ค่าประกันห้องพัก 5,000 บาท (คืนเมื่อย้ายออก)</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">การแจ้งซ่อม</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>แจ้งซ่อมได้ที่สำนักงานหอพักในเวลาทำการ</li>
                                <li>กรณีฉุกเฉินสามารถโทรแจ้งได้ที่ 081-234-5678</li>
                                <li>ค่าซ่อมแซมจากการใช้งานปกติทางหอพักเป็นผู้รับผิดชอบ</li>
                                <li>ค่าซ่อมแซมจากความประมาทผู้เช่าเป็นผู้รับผิดชอบ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apartment Facilities -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">สิ่งอำนวยความสะดวก</h3>
                        <x-button>
                            แก้ไขข้อมูล
                        </x-button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">ส่วนกลาง</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>ที่จอดรถ</li>
                                <li>กล้องวงจรปิด</li>
                                <li>ระบบคีย์การ์ด</li>
                                <li>Wi-Fi ฟรี</li>
                                <li>ร้านซักรีด</li>
                                <li>ร้านสะดวกซื้อ</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">ภายในห้องพัก</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>เครื่องปรับอากาศ</li>
                                <li>เครื่องทำน้ำอุ่น</li>
                                <li>ตู้เสื้อผ้า</li>
                                <li>โต๊ะทำงาน</li>
                                <li>เตียงนอน</li>
                                <li>ตู้เย็น</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</x-admin-layout>
