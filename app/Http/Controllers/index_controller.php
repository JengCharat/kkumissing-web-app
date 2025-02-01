<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Meter_reading;
use App\Models\Tenant;
use App\Models\Contract;

use App\Models\Booking;
class index_controller extends Controller
{
    //
    public function index(){
        $rooms = Room::all();
        return view('index',compact('rooms'));
    }
    // public function hire(Request $request){
    //     Room::create([
    //         'roomNumber' => $request->roomNumber,
    //     ]);
    public function hire(Request $request){
        $roomNumber = $request->roomNumber;
        $room = Room::where('roomNumber', $roomNumber)->firstOrFail();
        $room->update([
            'status' => "Not Available",
        ]);


        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = "daily";
        $tenant->telNumber = $request->tenantTel;
        $tenant->save(); // บันทึกข้อมูลลงในตาราง




        $contract = new Contract();
        $contract->start_date = $request->checkin;
        $contract->end_date = $request->checkout;
        $contract->room_id = $room->roomID;
        $contract->tenant_id = $tenant->tenantID;
        $contract_id = $contract->contractID;
        $contract->save();

        // ใช้ Eloquent ในการแทรกข้อมูล



        $booking = new Booking();
        $booking->tenant_id =$tenant->tenantID;
        $booking->check_in = $request->checkin;
        $booking->check_out = $request->checkout;
        $booking->save();
        return redirect('/index');


    }
}
