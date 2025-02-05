<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\MeterReading;
use App\Models\MeterDetails;
class DashboardController extends Controller
{
    //
    function index(){
        $userId = Auth::user()->id;
        $roomNumber = Auth::user()->name;

        $rooms = Room::where('roomnumber',$roomNumber)->first();
        $meter_reading = MeterReading::Where('room_id',$rooms->roomID)->first();
        $meter_detail = MeterDetails::Where('meter_detailID',$meter_reading->meter_details_id)->first();
        return view('dashboard',compact('userId','rooms','meter_reading','meter_detail'));
    }
}
