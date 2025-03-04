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

        $expense = Expense::where('room_id', $rooms->roomID)->first();

        $bills = Bill::where('roomID',$rooms->roomID)->first();
        if ($expense) {
        // if (True) {
            $rooms->water_price = (($meter_detail->water_meter_end) - ($meter_detail->water_meter_start)) * $expense->unit_price_water;
            $rooms->electricity_price = (($meter_detail->electricity_meter_end) - ($meter_detail->electricity_meter_start)) * $expense->unit_price_electricity;
            $rooms->save();

            //TODO:add overdue price
            $bills->total_price = $rooms->water_price + ($rooms->electricity_price);
            $bills->save();
        }
        //test
        return view('dashboard',compact('userId','rooms','meter_reading','meter_detail'));
    }
}
