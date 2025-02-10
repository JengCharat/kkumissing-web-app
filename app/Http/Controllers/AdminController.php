<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Expense;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use Illuminate\Http\Request;

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

        // Get meter readings for all rooms
        $meterReadings = MeterReading::with('meterdetails')->get();

        return view('admin', compact('Lrooms', 'Rrooms', 'unit_price_water', 'unit_price_electricity', 'meterReadings'));
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

        return redirect()->back()->with('success', 'Meter readings updated successfully');
    }
}
