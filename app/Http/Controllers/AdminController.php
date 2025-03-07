<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Room;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Contract;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\DB;

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
        // $Rooms = DB::table('rooms')
        // ->join('contracts', 'contracts.room_id', '=', 'rooms.roomID')
        // ->leftJoin('tenants','contracts.tenant_id','=','tenants.tenantID')
        // ->select('tenants.tenantName')
        // ->first();
        return view('admin.monthly-rooms', compact('Lrooms', 'Rrooms'));
    }

    /**
     * Daily rooms management
     */
    public function dailyRooms(Request $request)
    {
        // Get rooms with L and R prefixes
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        $check_in = $request->check_in;
        $check_out = $request->check_out;

        // Get all bookings if date filter is applied
        $bookings = null;
        $bookings_month = null;
        if ($check_in && $check_out) {
            $bookings = Contract::whereDate('start_date', '<=', $check_out)
                ->whereDate('end_date', '>=', $check_in)
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.tenantID')
                ->where('tenants.tenant_type', 'daily')
                ->select('contracts.*','tenants.*')
                ->orderBy('contracts.start_date', 'desc')
                ->get()
                ->groupBy('room_id');



            $bookings_month = Contract::whereDate('start_date', '<=', $check_out)
                ->whereDate('end_date', '>=', $check_in)
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.tenantID')
                ->where('tenants.tenant_type', 'monthly')
                ->select('contracts.*','tenants.*')
                ->orderBy('contracts.start_date', 'desc')
                ->get()
                ->groupBy('room_id');
        }
        if($bookings && $bookings->isNotEmpty()){
            $daily_room_id_that_has_been_taken = $bookings;
            $monthly_room_id_that_has_been_taken = $bookings_month;
        }
        else{
            $daily_room_id_that_has_been_taken = [];
            $monthly_room_id_that_has_been_taken = [];
        }
        // echo ("<h1>");
        // echo("xxxxxxxxxxxx");
        // echo($daily_room_id_that_has_been_taken[0]);
        // echo($bookings);
        // echo("</h1>");

        return view('admin.daily-rooms', compact('Lrooms', 'Rrooms','daily_room_id_that_has_been_taken','monthly_room_id_that_has_been_taken'));
    }

        // Get all bookings if date filter is applied
        $bookings = null;
        $bookings_month = null;
        if ($check_in && $check_out) {
            $bookings = Contract::whereDate('start_date', '<=', $check_out)
                ->whereDate('end_date', '>=', $check_in)
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.tenantID')
                ->where('tenants.tenant_type', 'daily')
                ->select('contracts.*','tenants.*')
                ->orderBy('contracts.start_date', 'desc')
                ->get()
                ->groupBy('room_id');



            $bookings_month = Contract::whereDate('start_date', '<=', $check_out)
                ->whereDate('end_date', '>=', $check_in)
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.tenantID')
                ->where('tenants.tenant_type', 'monthly')
                ->select('contracts.*','tenants.*')
                ->orderBy('contracts.start_date', 'desc')
                ->get()
                ->groupBy('room_id');
        }
        if($bookings && $bookings->isNotEmpty()){
            $daily_room_id_that_has_been_taken = $bookings;
            $monthly_room_id_that_has_been_taken = $bookings_month;
        }
        else{
            $daily_room_id_that_has_been_taken = [];
            $monthly_room_id_that_has_been_taken = [];
        }
        // echo ("<h1>");
        // echo("xxxxxxxxxxxx");
        // echo($daily_room_id_that_has_been_taken[0]);
        // echo($bookings);
        // echo("</h1>");

        return view('admin.daily-rooms', compact('Lrooms', 'Rrooms','daily_room_id_that_has_been_taken','monthly_room_id_that_has_been_taken'));
    }

    /**
     * Completed payments management
     */
    public function completedPayments()
    {
        $completedBills = Bill::where('status', 'ชำระแล้ว')
            ->with(['room', 'tenant'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.completed-payments', compact('completedBills'));
    }

    /**
     * Monthly tenants management
     */
    public function monthlyTenants()
    {
        // Get monthly tenants with their rooms and bookings
        $tenants = Tenant::where('tenant_type', 'monthly')
            ->with(['bookings.room', 'contracts'])
            ->get();

        return view('admin.monthly-tenants', compact('tenants'));
    }

    /**
     * Update monthly tenant
     */
    public function updateMonthlyTenant(Request $request)
    {
        $request->validate([
            'tenantID' => 'required|exists:tenants,tenantID',
            'tenantName' => 'required|string',
            'tenantTel' => 'required|string',
        ]);

        // Update tenant
        $tenant = Tenant::find($request->tenantID);
        $tenant->tenantName = $request->tenantName;
        $tenant->telNumber = $request->tenantTel;
        $tenant->save();

        return redirect()->route('admin.monthly-tenants')->with('success', 'อัพเดทข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    /**
     * Delete monthly tenant
     */
    public function deleteMonthlyTenant($tenantID)
    {
        $tenant = Tenant::find($tenantID);
        if (!$tenant) {
            return redirect()->route('admin.monthly-tenants')->with('error', 'ไม่พบข้อมูลลูกค้า');
        }

        // Get booking for this tenant
        $booking = Booking::where('tenant_id', $tenantID)->first();
        if ($booking) {
            // Get room for this booking
            $room = Room::find($booking->room_id);
            if ($room) {
                // Update room status to Available
                $room->status = 'Available';
                $room->save();

                // Find and clear meter readings for this room
                $meterReading = MeterReading::where('room_id', $room->roomID)->first();
                if ($meterReading) {
                    // Clear tenant association
                    $meterReading->tenant_id = null;
                    $meterReading->save();

                    // Reset meter details if they exist
                    if ($meterReading->meter_details_id) {
                        $meterDetails = MeterDetails::find($meterReading->meter_details_id);
                        if ($meterDetails) {
                            // Reset all meter readings to 0
                            $meterDetails->water_meter_start = 0;
                            $meterDetails->water_meter_end = 0;
                            $meterDetails->electricity_meter_start = 0;
                            $meterDetails->electricity_meter_end = 0;
                            $meterDetails->save();
                        }
                    }
                }
            }

            // Delete booking
            $booking->delete();
        }

        // Delete contracts
        Contract::where('tenant_id', $tenantID)->delete();

        // Delete tenant
        $tenant->delete();

        return redirect()->route('admin.monthly-tenants')->with('success', 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    /**
     * Daily tenants management
     */
    public function dailyTenants()
    {
        // Get daily tenants with their rooms and bookings
        $tenants = Tenant::where('tenant_type', 'daily')
            ->with(['bookings.room'])
            ->get();

        return view('admin.daily-tenants', compact('tenants'));
    }

    /**
     * Get available rooms for daily booking
     */
    public function getAvailableRoomsForDaily()
    {
        $rooms = Room::where('status', 'Available')->get();
        return response()->json(['rooms' => $rooms]);
    }

    /**
     * Create a new daily tenant/booking
     */
    public function createDailyTenant(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required|string',
            'tenantName' => 'required|string',
            'tenantTel' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'deposit' => 'required|numeric',
        ]);

        // Get room by room number
        $room = Room::where('roomNumber', $request->roomNumber)->firstOrFail();

        // Check if room is available
        if ($room->status != "Available") {
            return redirect()->back()->with('error', 'ห้องไม่ว่าง');
        }

        // Update room status to Not Available
        $room->update([
            'status' => "Not Available",
        ]);

        // Create new tenant
        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = 'daily';
        $tenant->telNumber = $request->tenantTel;
        $tenant->save();

        // Calculate number of days and total price
        $checkIn = new \DateTime($request->check_in);
        $checkOut = new \DateTime($request->check_out);
        $interval = $checkIn->diff($checkOut);
        $days = $interval->days;

        // Use daily rate from room if available, otherwise use a default
        $dailyRate = $room->daily_rate ?? 600; // Default daily rate if not set
        $totalPrice = $days * $dailyRate;

        // Create booking
        $booking = new Booking();
        $booking->tenant_id = $tenant->tenantID;
        $booking->room_id = $room->roomID;
        $booking->booking_type = 'daily';
        $booking->check_in = $request->check_in;
        $booking->check_out = $request->check_out;
        $booking->deposit = $request->deposit;
        $booking->save();

        return redirect()->route('admin.daily-tenants')->with('success', 'เพิ่มการจองห้องพักรายวันเรียบร้อยแล้ว');
    }

    /**
     * Update daily tenant
     */
    public function updateDailyTenant(Request $request)
    {
        $request->validate([
            'tenantID' => 'required|exists:tenants,tenantID',
            'tenantName' => 'required|string',
            'tenantTel' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Update tenant
        $tenant = Tenant::find($request->tenantID);
        $tenant->tenantName = $request->tenantName;
        $tenant->telNumber = $request->tenantTel;
        $tenant->save();

        // Update booking
        $booking = Booking::where('tenant_id', $tenant->tenantID)->first();
        if ($booking) {
            $booking->check_in = $request->check_in;
            $booking->check_out = $request->check_out;
            $booking->save();
        }

        return redirect()->route('admin.daily-tenants')->with('success', 'อัพเดทข้อมูลการจองเรียบร้อยแล้ว');
    }

    /**
     * Delete daily tenant/booking
     */
    public function deleteDailyTenant($tenantID)
    {
        $tenant = Tenant::find($tenantID);
        if (!$tenant) {
            return redirect()->route('admin.daily-tenants')->with('error', 'ไม่พบข้อมูลลูกค้า');
        }

        // Get booking for this tenant
        $booking = Booking::where('tenant_id', $tenantID)->first();
        if ($booking) {
            // Get room for this booking
            $room = Room::find($booking->room_id);
            if ($room) {
                // Update room status to Available
                $room->status = 'Available';
                $room->save();

                // Find and clear meter readings for this room
                $meterReading = MeterReading::where('room_id', $room->roomID)->first();
                if ($meterReading) {
                    // Clear tenant association
                    $meterReading->tenant_id = null;
                    $meterReading->save();
                }
            }

            // Delete booking
            $booking->delete();
        }

        // Delete contracts if any
        Contract::where('tenant_id', $tenantID)->delete();

        // Delete tenant
        $tenant->delete();

        return redirect()->route('admin.daily-tenants')->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }

    /**
     * Get daily tenant details
     */
    public function getDailyTenantDetails($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Get booking for this tenant
        $booking = Booking::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get room from booking
        $room = null;
        if ($booking && $booking->room_id) {
            $room = Room::find($booking->room_id);
        }

        // Calculate number of days and total price
        $days = 0;
        $totalPrice = 0;
        if ($booking && $booking->check_in && $booking->check_out) {
            $checkIn = new \DateTime($booking->check_in);
            $checkOut = new \DateTime($booking->check_out);
            $interval = $checkIn->diff($checkOut);
            $days = $interval->days;

            // Use daily rate from room if available, otherwise use a default
            $dailyRate = $room ? ($room->daily_rate ?? 600) : 600; // Default daily rate if not set
            $totalPrice = $days * $dailyRate;
        }

        return response()->json([
            'tenant' => $tenant,
            'booking' => $booking,
            'room' => $room,
            'days' => $days,
            'totalPrice' => $totalPrice
        ]);
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

        return redirect()->back()->with('success', 'ปรับราคาต่อหน่วยเสร็จสิ้น');
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

    /**
     * Get tenant details for a room
     */
    public function getRoomTenant($roomId)
    {
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        // Get booking for this room
        $booking = Booking::where('room_id', $roomId)
            ->where('booking_type', 'monthly')
            ->orderBy('created_at', 'desc')
            ->first();

        // Get tenant from booking
        $tenant = null;
        if ($booking && $booking->tenant_id) {
            $tenant = Tenant::find($booking->tenant_id);
        }

        // Get meter readings for this room
        $meterReading = MeterReading::with('meterdetails')
            ->where('room_id', $roomId)
            ->first();

        return response()->json([
            'room' => $room,
            'booking' => $booking,
            'tenant' => $tenant,
            'meter_readings' => $meterReading
        ]);
    }

    /**
     * Get tenant details by tenant ID
     */
    public function getTenantDetails($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Get booking for this tenant
        $booking = Booking::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get room from booking
        $room = null;
        if ($booking && $booking->room_id) {
            $room = Room::find($booking->room_id);
        }

        // Get contract for this tenant
        $contract = Contract::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'tenant' => $tenant,
            'booking' => $booking,
            'room' => $room,
            'contract' => $contract
        ]);
    }

    /**
     * Get bills for a room
     */
    public function getRoomBills($roomId)
    {
        $bills = Bill::where('roomID', $roomId)
            ->orderBy('BillDate', 'desc')
            ->get();

        return response()->json(['bills' => $bills]);
    }

    /**
     * Get bill details
     */
    public function getBill($billId)
    {
        $bill = Bill::find($billId);
        if (!$bill) {
            return response()->json(['error' => 'Bill not found'], 404);
        }

        // Calculate water and electricity units based on prices
        $room = Room::find($bill->roomID);
        $water_units = 0;
        $electricity_units = 0;

        if ($room) {
            if ($room->water_price > 0 && $bill->water_price > 0) {
                $water_units = $bill->water_price / $room->water_price;
            }
            if ($room->electricity_price > 0 && $bill->electricity_price > 0) {
                $electricity_units = $bill->electricity_price / $room->electricity_price;
            }
        }

        return response()->json([
            'bill' => $bill,
            'water_units' => $water_units,
            'electricity_units' => $electricity_units
        ]);
    }

    /**
     * Create or update a bill
     */
    public function createBill(Request $request)
    {
        // $request->validate([
        //     'roomID' => 'required|exists:rooms,roomID',
        //     'tenantID' => 'required|exists:tenants,tenantID',
        //     'billing_month' => 'required|integer|min:1|max:12',
        //     'billing_year' => 'required|integer|min:2000|max:2100',
        //     'water_meter_start' => 'required|numeric|min:0',
        //     'water_meter_end' => 'required|numeric|min:0',
        //     'electricity_meter_start' => 'required|numeric|min:0',
        //     'electricity_meter_end' => 'required|numeric|min:0',
        //     'water_units' => 'required|numeric|min:0',
        //     'electricity_units' => 'required|numeric|min:0',
        //     'water_rate' => 'required|numeric|min:0',
        //     'electricity_rate' => 'required|numeric|min:0',
        //     'room_rate' => 'required|numeric|min:0',
        //     'damage_fee' => 'nullable|numeric|min:0',
        //     'overdue_fee' => 'nullable|numeric|min:0',
        //     'water_price' => 'required|numeric|min:0',
        //     'electricity_price' => 'required|numeric|min:0',
        //     'total_price' => 'required|numeric|min:0',
        // ]);

        // Create bill date from month and year
        $billDate = date('Y-m-d', strtotime($request->billing_year . '-' . $request->billing_month . '-01'));

        // Check if we're updating an existing bill
        if ($request->has('billId')) {
            $bill = Bill::find($request->billId);
            if (!$bill) {
                return redirect()->back()->with('error', 'Bill not found');
            }
        } else {
            // Check if a bill already exists for this room and month
            $existingBill = Bill::where('roomID', $request->roomID)
                ->whereYear('BillDate', $request->billing_year)
                ->whereMonth('BillDate', $request->billing_month)
                ->first();

            if ($existingBill) {
                return redirect()->back()->with('error', 'ไม่สามารถออกบิลซ้ำในเดือนเดียวกันได้ กรุณาแก้ไขบิลที่มีอยู่แล้ว');
            }

            // Create a new bill
            $bill = new Bill();
            $tenantId = Contract::where('room_id',$request->roomID)->first();
            // Set bill data
            $bill->roomID = $request->roomID;
            $bill->tenantID = $tenantId->tenant_id;
            $bill->BillDate = $billDate;
            // $bill->room_rate = $request->room_rate;
            $bill->damage_fee = $request->damage_fee;
            $bill->overdue_fee = $request->overdue_fee;
            $bill->water_price = $request->water_price;
            $bill->electricity_price = $request->electricity_price;
            $bill->total_price = $request->total_price;
            // $bill->status = 'รอชำระเงิน'; // Set status with default if not provided
            $bill->save();

        }

        // $tenantId = Contract::where('room_id',$request->roomID);
        // // Set bill data
        // $bill->roomID = $request->roomID;
        // $bill->tenantID = $tenantId;
        // $bill->BillDate = $billDate;
        // $bill->room_rate = $request->room_rate;
        // $bill->damage_fee = $request->damage_fee;
        // $bill->overdue_fee = $request->overdue_fee;
        // $bill->water_price = $request->water_price;
        // $bill->electricity_price = $request->electricity_price;
        // $bill->total_price = $request->total_price;
        // $bill->status = $request->status ?? 'รอชำระเงิน'; // Set status with default if not provided
        // $bill->save();
        //
        // Update meter readings
        $meterReading = MeterReading::firstOrCreate(
            ['room_id' => $request->roomID],
            [
                'reading_date' => now(),
                'start_date' => now(),
                'end_date' => now(),
                'tenant_id' => $request->tenantID
            ]
        );

        // Get or create meter details
        if ($meterReading->meter_details_id) {
            $meterDetails = MeterDetails::find($meterReading->meter_details_id);
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

        return redirect()->route('admin.monthly-rooms')->with('success', 'Bill saved successfully');
    }

    /**
     * Delete a bill
     */
    public function deleteBill($billId)
    {
        $bill = Bill::find($billId);
        if (!$bill) {
            return response()->json(['success' => false, 'message' => 'Bill not found'], 404);
        }

        $bill->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Mark a bill as paid
     */
    public function markBillAsPaid($billId)
    {
        $bill = Bill::find($billId);
        if (!$bill) {
            return response()->json(['success' => false, 'message' => 'Bill not found'], 404);
        }

        $bill->status = 'ชำระแล้ว';
        $bill->save();

        return response()->json(['success' => true]);
    }

    /**
     * Print receipt for a bill
     */
    public function printReceipt($billId)
    {
        $bill = Bill::with(['room', 'tenant'])->find($billId);
        if (!$bill) {
            return redirect()->back()->with('error', 'Bill not found');
        }

        // Return a view with the receipt
        return view('admin.receipt', compact('bill'));
    }

    /**
     * Get available rooms for monthly tenant
     */
    public function getAvailableRooms()
    {
        $rooms = Room::where('status', 'Available')->get();
        return response()->json(['rooms' => $rooms]);
    }

    /**
     * Create a new monthly tenant
     */
    public function createMonthlyTenant(Request $request)
    {
        $request->validate([
            'roomNumber' => 'required|string',
            'tenantName' => 'required|string',
            'tenantTel' => 'required|string',
            'due_date' => 'required|date',
            'deposit' => 'required|numeric',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Get room by room number
        $room = Room::where('roomNumber', $request->roomNumber)->firstOrFail();

        // Check if room is available
        if ($room->status != "Available") {
            return redirect()->back()->with('error', 'ห้องไม่ว่าง');
        }

        // Update room status to Not Available
        $room->update([
            'status' => "Not Available",
        ]);

        // Create new tenant
        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = $request->tenant_type;
        $tenant->telNumber = $request->tenantTel;

        // For monthly tenants, use the room's user
        $users = User::where('name', $request->roomNumber)->first();
        if ($users) {
            $tenant->user_id_tenant = $users->id;
            $users->password = bcrypt($request->tenantTel);
            $users->save();
        } else {
            // If no user found, use admin user
            $adminUser = User::where('email', 'admin@gmail.com')->first();
            if ($adminUser) {
                $tenant->user_id_tenant = $adminUser->id;
            }
        }

        $tenant->save();

        // Update meter reading with tenant
        $meter_reading = MeterReading::where('room_id', $room->roomID)->first();
        if ($meter_reading) {
            $meter_reading->tenant_id = $tenant->tenantID;
            $meter_reading->save();
        }

        // Create contract
        $contract = new Contract();
        $contract->start_date = today(); // Use today as start date
        $contract->end_date = $request->due_date; // Use due date as end date
        $contract->room_id = $room->roomID;
        $contract->tenant_id = $tenant->tenantID;
        $contract->contract_date = today();
        $contract->save();

        // Handle contract file upload
        $file = $request->file('img');
        $filename = $contract->contractID . '-' . $tenant->tenantID . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('upload', $filename, 'public');
        $contract->contract_file = $path;
        $contract->save();

        // Create booking
        $booking = new Booking();
        $booking->tenant_id = $tenant->tenantID;
        $booking->room_id = $room->roomID;
        $booking->booking_type = $request->tenant_type;
        $booking->check_in = today(); // Use today as check-in date
        $booking->check_out = $request->due_date; // Use due date as check-out date
        $booking->due_date = $request->due_date;
        $booking->deposit = $request->deposit;
        $booking->save();

        return redirect()->route('admin.monthly-tenants')->with('success', 'เพิ่มลูกค้ารายเดือนเรียบร้อยแล้ว');
    }
    public function pendingPayments()
        {
            $pendingBills = Bill::where('status', 'รอชำระเงิน')
                ->with(['room', 'tenant'])
                ->orderBy('BillDate', 'desc')
                ->get();

            return view('admin.pending-payments', compact('pendingBills'));
        }
}
