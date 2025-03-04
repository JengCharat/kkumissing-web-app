<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Expense;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;

class AdminController extends Controller
{
    /**
     * Room management methods
     */
    public function rooms()
    {
        // Get rooms with L and R prefixes
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        return view('admin.rooms', compact('Lrooms', 'Rrooms'));
    }

    /**
     * Monthly rooms management
     */
    public function monthlyRooms()
    {
        // Get rooms with L and R prefixes
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        return view('admin.monthly-rooms', compact('Lrooms', 'Rrooms'));
    }

    /**
     * Daily rooms management
     */
    public function dailyRooms()
    {
        // Get rooms with L and R prefixes
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        return view('admin.daily-rooms', compact('Lrooms', 'Rrooms'));
    }

    /**
     * Pending payments management
     */
    public function pendingPayments()
    {
        return view('admin.pending-payments');
    }

    /**
     * Completed payments management
     */
    public function completedPayments()
    {
        return view('admin.completed-payments');
    }

    /**
     * Monthly tenants management
     */
    public function monthlyTenants()
    {
        return view('admin.monthly-tenants');
    }

    /**
     * Daily tenants management
     */
    public function dailyTenants()
    {
        return view('admin.daily-tenants');
    }

    /**
     * Apartment information management
     */
    public function apartmentInfo()
    {
        return view('admin.apartment-info');
    }

    /**
     * Room types management
     */
    public function roomTypes()
    {
        return view('admin.room-types');
    }

    /**
     * Utilities management
     */
    public function utilities()
    {
        // Get current unit prices
        $latestExpense = Expense::orderBy('created_at', 'desc')->first();
        $unit_price_water = $latestExpense ? $latestExpense->unit_price_water : null;
        $unit_price_electricity = $latestExpense ? $latestExpense->unit_price_electricity : null;

        return view('admin.utilities', compact('unit_price_water', 'unit_price_electricity'));
    }

    /**
     * Admin settings
     */
    public function settings()
    {
        $admin = auth()->user();
        return view('admin.settings', compact('admin'));
    }

    /**
     * Update admin profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $updater = new UpdateUserProfileInformation();
        $updater->update($user, $request->all());

        return redirect()->route('admin.settings')->with('success', 'ข้อมูลส่วนตัวถูกอัพเดทเรียบร้อยแล้ว');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $updater = new UpdateUserPassword();
        $updater->update($user, $request->all());

        return redirect()->route('admin.settings')->with('success', 'รหัสผ่านถูกอัพเดทเรียบร้อยแล้ว');
    }

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
