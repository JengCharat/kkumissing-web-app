<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use App\Models\Expense;
use App\Models\Bill;
class DashboardController extends Controller
{
    //
    function index(){
        $userId = Auth::user()->id;
        $roomNumber = Auth::user()->name;

        $rooms = Room::where('roomnumber', $roomNumber)->first();

        if (!$rooms) {
            return redirect()->back()->with('error', 'Room not found');
        }

        $meter_reading = MeterReading::where('room_id', $rooms->roomID)->first();

        if (!$meter_reading) {
            return redirect()->back()->with('error', 'Meter reading not found');
        }

        $meter_detail = MeterDetails::where('meter_detailID', $meter_reading->meter_details_id)->first();

        if (!$meter_detail) {
            return redirect()->back()->with('error', 'Meter details not found');
        }

        // Get the latest bill for this room
        $bill = Bill::where('roomID', $rooms->roomID)
                    ->orderBy('BillDate', 'desc')
                    ->first();

        //test
        return view('dashboard',compact('userId','rooms','meter_reading','meter_detail','bill'));
    }

    function upload_slip(Request $request){
        $bills = Bill::where('roomID', $request->roomID)
        ->orderBy('created_at', 'desc')  // Order by the creation time
        ->first();  // Get the first (most recent) record


        $request->validate([
            'slip_image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $file = $request->file('slip_image');

        $filename = 'slip-image-date'.'-'."xxx" .'.' . $file->getClientOriginalExtension(); // ตั้งชื่อไฟล์ใหม่ (timestamp + นามสกุลเดิม)
        // $file->store('slips', 'public');
        $path = $file->storeAs('slips', $filename, 'public');
        $bills->status = "ชำระแล้ว";
        $bills->slip_file = $path;
        $bills->save();

        $status = $bills->status;
        return redirect('dashboard')->with('status', $status);
    }

}
