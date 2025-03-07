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

        $expense = Expense::where('expenseID', 1)->first();
        if ($expense) {
            $bill = Bill::where('roomID', $rooms->roomID)->first();
            if ($bill) {
                $bill->water_price = (($meter_detail->water_meter_end) - ($meter_detail->water_meter_start)) * $expense->unit_price_water;
                $bill->electricity_price = (($meter_detail->electricity_meter_end) - ($meter_detail->electricity_meter_start)) * $expense->unit_price_electricity;
                $bill->save();
            }
        }
        // Get the most recent bill for this room
        $bill = Bill::where('roomID', $rooms->roomID)
            ->orderBy('created_at', 'desc')
            ->first();

        // Default values if no bill is found
        $status = null;
        $bills_id = null;

        if ($bill) {
            $status = $bill->status;
            $bills_id = $bill->BillNo;
        }

        return view('dashboard', compact('userId', 'rooms', 'meter_reading', 'meter_detail', 'status', 'bills_id', 'bill'));
    }




     function upload_slip(Request $request){
            // Get the bill record
            $bill = Bill::where('roomID', $request->roomID)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$bill) {
                return redirect('dashboard')->with('error', 'Bill not found');
            }

            $request->validate([
                'slip_image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $file = $request->file('slip_image');

            // Generate a unique filename with timestamp
            $filename = 'slip-image-' . date('Ymd-His') . '-' . $bill->BillNo . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('slips', $filename, 'public');

            // Update bill status and slip file path
            $bill->status = "ชำระแล้ว";
            $bill->slip_file = $path;
            $bill->save();

            $status = $bill->status;
            return redirect('dashboard')->with('status', $status);
        }

        public function printReceipt($billId)
        {
            $bill = Bill::with(['room', 'tenant'])->find($billId);
            if (!$bill) {
                return redirect()->back()->with('error', 'Bill not found');
            }

            // Return a view with the receipt
            return view('admin.receipt', compact('bill'));
        }




     function upload_slip(Request $request){
            $bills = Bill::where('roomID', $request->roomID)
            ->orderBy('created_at', 'desc')  // Assuming you want to order by the creation time
            ->first();  // Get the first (most recent) record


            $request->validate([
                'slip_image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $file = $request->file('slip_image');

            $filename = 'slip-image-date'.'-'."xxx" .'.' . $file->getClientOriginalExtension(); // ตั้งชื่อไฟล์ใหม่ (timestamp + นามสกุลเดิม)
            $path = $file->storeAs('upload', $filename, 'public');
            // $file->store('upload', 'public');
            $bills->status = "ชำระแล้ว";
            $bills->slip_file = $path;
            $bills->save();

            $status = $bills->status;
            return redirect('dashboard')->with('status', $status);

        }

        public function printReceipt($billId)
        {
            $bill = Bill::with(['room', 'tenant'])->find($billId);
            if (!$bill) {
                return redirect()->back()->with('error', 'Bill not found');
            }

            // Return a view with the receipt
            return view('admin.receipt', compact('bill'));
        }

}
