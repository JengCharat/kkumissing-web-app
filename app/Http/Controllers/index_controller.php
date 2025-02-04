<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\MeterReading;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\User;
use App\Models\Booking;
class index_controller extends Controller
{
    //
    public function index(){
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();
        return view('index',compact('Lrooms','Rrooms'));
    }
    // public function hire(Request $request){
    //     Room::create([
    //         'roomNumber' => $request->roomNumber,
    //     ]);
    public function hire(Request $request){
        $roomNumber = $request->roomNumber;
        $room = Room::where('roomNumber', $roomNumber)->firstOrFail();
        if($room->status == "Not Available"){
            abort(404);
        }
        $room->update([
            'status' => "Not Available",
        ]);






        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = $request->tenant_type;
        if($request->tenant_type == "monthly"){
            // $users = User::find($roomNumber);
            $users = User::where('name', $request->roomNumber)->first();
            $tenant->user_id_tenant = $users -> id;
            $users->password = bcrypt($request->tenantTel);
            $users->save();
        }
        $tenant->telNumber = $request->tenantTel;
        $tenant->save(); // บันทึกข้อมูลลงในตาราง

        $meter_reading = MeterReading::where('room_id',$room->roomID)->firstOrFail();
        $meter_reading->tenant_id = $tenant->tenantID;
        $meter_reading->save();
        // if ($meter_reading) {
        //     // return $meter_reading->tenant_id;
        //     $meter_reading->update([
        //         // 'tenant_id' => $tenant->tenant_id,
        //         'tenant_id' => 1,
        //
        //     ]);
        // }
        // else{
        //     abort(404);
        // }






        $contract = new Contract();
        $contract->start_date = $request->checkin;
        $contract->end_date = $request->checkout;
        $contract->room_id = $room->roomID;
        $contract->tenant_id = $tenant->tenantID;
        $contract->contract_date = today();
        $contract->save();
        $contract_id = $contract->contractID;
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $file = $request->file('img');

        $filename = $contract_id.'-'.$tenant->tenantID. '.' . $file->getClientOriginalExtension(); // ตั้งชื่อไฟล์ใหม่ (timestamp + นามสกุลเดิม)
        $path = $file->storeAs('upload', $filename, 'public');
        // $file->store('upload', 'public');

        $contract->contract_file = $path;
        $contract->save();


        $booking = new Booking();
        $booking->tenant_id =$tenant->tenantID;
        $booking->booking_type = $request->tenant_type;
        $booking->check_in = $request->checkin;
        $booking->check_out = $request->checkout;
        $booking->due_date = $request->due_date;
        $booking->deposit =  $request->deposit;
        $booking->save();


        return redirect('/index');
    }
    public function showImage($contractID)
{
    $contract = Contract::find($contractID); // ค้นหา contract ตาม ID

    if (!$contract || !$contract->contract_file) {
        abort(404); // ถ้าไม่เจอไฟล์ ให้แสดง 404
    }

    // คืนค่า URL ของไฟล์รูปภาพ
    return response()->file(storage_path('app/public/' . $contract->contract_file));
        // /////////////how to show
        // Route::get('/contract/image/{id}', [ContractController::class, 'showImage']);
        // <img src="{{ url('/contract/image/' . $contract->id) }}" alt="Contract Image">

}
}
