<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดประเภทห้องพัก') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Room Types -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">ประเภทห้องพัก</h3>
                        <x-button>
                            + เพิ่มประเภทห้อง
                        </x-button>
                    </div>

                    <!-- Room Types Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">รหัส</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ชื่อประเภท</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ราคา/เดือน</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ราคา/วัน</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ขนาด (ตร.ม.)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">สิ่งอำนวยความสะดวก</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">จำนวนห้อง</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <!-- Sample data rows -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">RT001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">ห้องมาตรฐาน</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">4,500 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">600 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">20</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <ul class="list-disc pl-5">
                                            <li>เครื่องปรับอากาศ</li>
                                            <li>เครื่องทำน้ำอุ่น</li>
                                            <li>เตียงเดี่ยว</li>
                                            <li>โต๊ะทำงาน</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">12</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2">แก้ไข</button>
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">ลบ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">RT002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">ห้องพิเศษ</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">5,500 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">700 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">25</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <ul class="list-disc pl-5">
                                            <li>เครื่องปรับอากาศ</li>
                                            <li>เครื่องทำน้ำอุ่น</li>
                                            <li>เตียงคู่</li>
                                            <li>โต๊ะทำงาน</li>
                                            <li>ตู้เย็น</li>
                                            <li>โทรทัศน์</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">8</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2">แก้ไข</button>
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">ลบ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">RT003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">ห้องสวีท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">7,500 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">900 บาท</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">35</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <ul class="list-disc pl-5">
                                            <li>เครื่องปรับอากาศ</li>
                                            <li>เครื่องทำน้ำอุ่น</li>
                                            <li>เตียงคู่</li>
                                            <li>โต๊ะทำงาน</li>
                                            <li>ตู้เย็น</li>
                                            <li>โทรทัศน์</li>
                                            <li>โซฟา</li>
                                            <li>ไมโครเวฟ</li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">4</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2">แก้ไข</button>
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">ลบ</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Room Assignment -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">กำหนดประเภทห้อง</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">เลือกห้อง</h4>
                            <div class="mb-4">
                                <x-label for="room_number" value="หมายเลขห้อง" />
                                <select id="room_number" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- เลือกห้อง --</option>
                                    <option value="L001">L001</option>
                                    <option value="L002">L002</option>
                                    <option value="L003">L003</option>
                                    <option value="R001">R001</option>
                                    <option value="R002">R002</option>
                                    <option value="R003">R003</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">เลือกประเภทห้อง</h4>
                            <div class="mb-4">
                                <x-label for="room_type" value="ประเภทห้อง" />
                                <select id="room_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- เลือกประเภทห้อง --</option>
                                    <option value="RT001">ห้องมาตรฐาน</option>
                                    <option value="RT002">ห้องพิเศษ</option>
                                    <option value="RT003">ห้องสวีท</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button>
                            บันทึก
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
