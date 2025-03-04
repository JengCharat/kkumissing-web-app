<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดการ Admin') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Profile -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">ข้อมูลผู้ดูแลระบบ</h3>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">ข้อมูลส่วนตัว</h4>
                            <form action="{{ route('admin.update-profile') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <x-label for="name" value="ชื่อ-นามสกุล" />
                                    <x-input id="name" name="name" type="text" class="block mt-1 w-full" value="{{ $admin->name }}" required />
                                </div>
                                <div class="mb-4">
                                    <x-label for="email" value="อีเมล" />
                                    <x-input id="email" name="email" type="email" class="block mt-1 w-full" value="{{ $admin->email }}" required />
                                </div>
                                <div class="mt-4">
                                    <x-button>
                                        บันทึกข้อมูล
                                    </x-button>
                                </div>
                            </form>
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">เปลี่ยนรหัสผ่าน</h4>
                            <form action="{{ route('admin.update-password') }}" method="POST">
                                @csrf
                                {{-- <div class="mb-4">
                                    <x-label for="current_password" value="รหัสผ่านปัจจุบัน" />
                                    <x-input id="current_password" name="current_password" type="password" class="block mt-1 w-full" required />
                                </div> --}}
                                <div class="mb-4">
                                    <x-label for="password" value="รหัสผ่านใหม่" />
                                    <x-input id="password" name="password" type="password" class="block mt-1 w-full" required />
                                </div>
                                <div class="mb-4">
                                    <x-label for="password_confirmation" value="ยืนยันรหัสผ่านใหม่" />
                                    <x-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" required />
                                </div>
                                <div class="mt-4">
                                    <x-button>
                                        เปลี่ยนรหัสผ่าน
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
