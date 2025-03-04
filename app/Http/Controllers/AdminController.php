<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Expense;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use Illuminate\Http\Request;
use App\Models\Bill;
class AdminController extends Controller
{
    public function index()
    {
        // Get rooms with L and R prefixes
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        // Get current unit prices
        $latestExpense = Expense::orderBy('created_at', 'desc')->first();
        $unit_price_water = $latestExpense ? $latestExpense->unit_price_water : null;
        $unit_price_electricity = $latestExpense ? $latestExpense->unit_price_electricity : null;

        // $bills = Bill::all();

        $bills = DB::table('bills')
            ->join('rooms', 'bills.roomID', '=', 'rooms.roomID') // JOIN กับตาราง rooms
            ->join('tenants', 'bills.tenant_id', '=', 'tenants.tenantID')
            ->leftJoin('bookings','bills.tenant_id','=','bookings.tenant_id')
            ->select('bills.billID','bills.total_price','bills.status', 'rooms.roomNumber', 'rooms.daily_rate','rooms.overdue_fee_rate','tenants.tenantName','bookings.damage_fee')
            ->get();
        // Get meter readings for all rooms
        $meterReadings = MeterReading::with('meterdetails')->get();

        return view('admin', compact('Lrooms', 'Rrooms', 'unit_price_water', 'unit_price_electricity', 'meterReadings','bills'));
    }

    public function updateUnitPrices(Request $request)
    {
        $request->validate([
            'unit_price_water' => 'required|numeric|min:0',
            'unit_price_electricity' => 'required|numeric|min:0'
        ]);

        // Update all expenses with new unit prices
        Expense::query()->update([
            'unit_price_water' => $request->unit_price_water,
            'unit_price_electricity' => $request->unit_price_electricity,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Unit prices updated successfully');
    }

    public function updateMeterReadings(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required|string',
            'water_meter_start' => 'required|numeric|min:0',
            'water_meter_end' => 'required|numeric|min:0',
            'electricity_meter_start' => 'required|numeric|min:0',
            'electricity_meter_end' => 'required|numeric|min:0',
        ]);

        // Get room ID from room number
        $room = Room::where('roomNumber', $request->roomNumber)->first();
        if (!$room) {
            return redirect()->back()->with('error', 'Room not found');
        }

        // Get or create meter reading for the room
        $meterReading = MeterReading::firstOrCreate(
            ['room_id' => $room->roomID],
            [
                'reading_date' => now(),
                'start_date' => now(),
                'end_date' => now(),
            ]
        );

        // Get or create meter details
        if ($meterReading->meter_details_id) {
            $meterDetails = MeterDetails::where('meter_detailID', $meterReading->meter_details_id)->first();
            if (!$meterDetails) {
                $meterDetails = new MeterDetails();
            }
        } else {
            $meterDetails = new MeterDetails();
        }

        // Save meter details
        $meterDetails->fill([
            'water_meter_start' => $request->water_meter_start,
            'water_meter_end' => $request->water_meter_end,
            'electricity_meter_start' => $request->electricity_meter_start,
            'electricity_meter_end' => $request->electricity_meter_end,
        ]);
        $meterDetails->save();

        // Update meter reading with meter details ID
        $meterReading->meter_details_id = $meterDetails->meter_detailID;
        $meterReading->save();



        $expense = Expense::where('room_id', $room->roomID)->first();

        $bills = Bill::where('roomID',$room->roomID)->first();
        if ($expense) {
        // if (True) {
            $room->water_price = (($meterDetails ->water_meter_end) - ($meterDetails ->water_meter_start)) * $expense->unit_price_water;
            $room->electricity_price = (($meterDetails ->electricity_meter_end) - ($meterDetails->electricity_meter_start)) * $expense->unit_price_electricity;
            $room->save();
        }

        $bill = new Bill();
        $bill->roomID = $room->roomID;
        $bill->tenant_id = $meterReading->tenant_id;
        $bill->total_price = $room->water_price + ($room->electricity_price);
        $bill->save();





        return redirect()->back()->with('success', 'Meter readings updated successfully');
    }

    /**
     * Update room status (Available/Not Available)
     * This is used when a tenant checks out or is no longer in the apartment
     */
    public function updateRoomStatus(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required|string',
            'status' => 'required|string|in:Available,Not Available',
        ]);

        // Get room by room number
        $room = Room::where('roomNumber', $request->roomNumber)->first();
        if (!$room) {
            return redirect()->back()->with('error', 'Room not found');
        }

        // Update room status
        $room->update([
            'status' => $request->status,
        ]);

        // If changing to Available, we might want to handle tenant checkout logic here
        if ($request->status === 'Available') {
            // Get the meter reading for this room
            $meterReading = MeterReading::where('room_id', $room->roomID)->first();

            // If there's a meter reading with a tenant, we can clear the tenant association
            if ($meterReading && $meterReading->tenant_id) {
                $meterReading->tenant_id = null;
                $meterReading->save();
            }
        }

        return redirect()->back()->with('success', 'Room status updated successfully');
    }
}
