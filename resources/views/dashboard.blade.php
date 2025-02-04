<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />

                roomID: {{$userId}}
                <br>
                room name: {{$rooms->roomNumber}}
                <h1>pay ment</h1>
                water_price: {{$rooms->water_price}}
                <br>
                electricity_price: {{$rooms->electricity_price}}


            </div>
        </div>
    </div>
</x-app-layout>
