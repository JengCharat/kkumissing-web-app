<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Meter_reading;
use App\Models\Tenant;
use App\Models\Contract;
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
            'state' => "Not Available",
        ]);


        $contract = new Contract();
        $contract->contract_start_date = $request->checkin;
        $contract->contract_end_date = $request->checkout;
        $contract->file_path = "xxxx/xxx.pdf";
        $contract->roomNumber = $request->roomNumber;
        $contract_id = $contract->contractID;
        $contract->save();

        // ใช้ Eloquent ในการแทรกข้อมูล
        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = "1";
        $tenant->telNumber = $request->tenantTel;
        $tenant->roomNumber = $request->roomNumber;
        $tenant->contractID = $contract_id;
        $tenant->save(); // บันทึกข้อมูลลงในตาราง



        return redirect('/index');


    }
}
