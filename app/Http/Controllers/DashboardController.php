<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use App\Models\Expense;
class DashboardController extends Controller
{
    //
    function index(){
        $userId = Auth::user()->id;
        $roomNumber = Auth::user()->name;

        $rooms = Room::where('roomnumber',$roomNumber)->first();
        $meter_reading = MeterReading::Where('room_id',$rooms->roomID)->first();
        $meter_detail = MeterDetails::Where('meter_detailID',$meter_reading->meter_details_id)->first();

        $expense = Expense::Where('expenseID',1)->first();
        $rooms->water_price =(($meter_detail->water_meter_end)-($meter_detail->water_meter_start)) * $expense->unit_price_water;
        $rooms->electricity_price = (($meter_detail->electricity_meter_end) - ($meter_detail->electricity_meter_start)) * $expense->unit_price_electricity;
        $rooms->save();
        return view('dashboard',compact('userId','rooms','meter_reading','meter_detail'));
    }
}
