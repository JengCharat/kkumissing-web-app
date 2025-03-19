<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการจอง - วราภรณ์ แมนชั่น</title>
    <!-- Include Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">ประวัติการจอง</h1>
                        <button onclick="openIndexPage()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            กลับไปหน้าหลัก
                        </button>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4">ค้นหาประวัติการจอง</h2>
                        <form action="/history" method="post" class="flex flex-wrap gap-4">
                            @csrf
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="telNum" placeholder="กรอกเบอร์โทรศัพท์"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    ค้นหา
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(count($tenant) > 0)
                        <div class="space-y-4">
                            @foreach ($tenant as $item)
                                <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-800">{{$item->tenantName}}</h3>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-gray-600">
                                                    <span class="font-medium">ประเภทการเช่า:</span>
                                                    <span class="ml-2 px-2 py-1 text-sm rounded-full {{ $item->tenant_type == 'daily' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                        {{ $item->tenant_type == 'daily' ? 'รายวัน' : 'รายเดือน' }}
                                                    </span>
                                                </p>

                                                @if ($item->tenant_type == 'daily')
                                                    <p class="text-gray-600">
                                                        <span class="font-medium">เช็คอิน:</span>
                                                        <span class="ml-2">{{$item->check_in}}</span>
                                                    </p>
                                                    <p class="text-gray-600">
                                                        <span class="font-medium">เช็คเอาท์:</span>
                                                        <span class="ml-2">{{$item->check_out}}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">ไม่พบข้อมูลการจอง</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function openIndexPage() {
            window.location.href = '/';
        }
    </script>
</body>
</html>
