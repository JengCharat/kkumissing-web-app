<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\MeterReading;
use App\Models\MeterDetails;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\User;
use App\Models\Booking;
class index_controller extends Controller
{
    //
public function index(Request $request){
        $Lrooms = Room::where('roomNumber', 'like', 'L%')->get();
        $Rrooms = Room::where('roomNumber', 'like', 'R%')->get();

        // Get check-in and check-out dates from request, supporting both naming conventions
        $check_in = $request->checkin ?? $request->check_in;
        $check_out = $request->checkout ?? $request->check_out;

        // Get all bookings if date filter is applied
        $bookings = null;
        if ($check_in && $check_out) {
            $bookings = Contract::whereDate('start_date', '<=', $check_out)
                ->whereDate('end_date', '>=', $check_in)
                ->get()
                ->groupBy('room_id');
        }

        if($bookings && $bookings->isNotEmpty()){
            $room_id_that_has_been_taken = $bookings->keys()->toArray();
        }
        else{
            $room_id_that_has_been_taken = [];
        }
        return view('index', compact('Lrooms', 'Rrooms', 'bookings', 'check_in', 'check_out','room_id_that_has_been_taken'));
    }

    public function getRoomBookings($roomId) {
        $room = Room::findOrFail($roomId);
        $bookings = $room->bookings()->orderBy('check_in')->get();

        return response()->json([
            'room' => $room,
            'bookings' => $bookings
        ]);
    }
    // public function hire(Request $request){
    //     Room::create([
    //         'roomNumber' => $request->roomNumber,
    //     ]);
    public function hire(Request $request){
        $roomNumber = $request->roomNumber;
        $room = Room::where('roomNumber', $roomNumber)->firstOrFail();

        // Check if room is available
        // if($room->status == "Not Available"){
        //     return redirect()->back()->with('error', 'ห้องไม่ว่าง กรุณาเลือกห้องอื่น');
        // }


        // Check if there are any existing bookings for this room in the specified date range
        $existingBookings = Contract::where('room_id', $room->roomID)
            ->whereDate('start_date', '<=', $request->checkout)
            ->whereDate('end_date', '>=', $request->checkin)
            ->exists();

        if ($existingBookings) {
            return redirect()->back()->with('error', 'ห้องนี้ถูกจองในช่วงเวลาที่เลือกแล้ว กรุณาเลือกห้องหรือวันที่อื่น');
        }


        $room->update([
            'status' => "Not Available",
        ]);

        $tenant = new Tenant();
        $tenant->tenantName = $request->tenantName;
        $tenant->tenant_type = $request->tenant_type;

        if($request->tenant_type == "monthly"){
            // For monthly tenants, use the room's user
            $users = User::where('name', $request->roomNumber)->first();
            $tenant->user_id_tenant = $users->id;
            $users->password = bcrypt($request->tenantTel);
            $users->save();
        } else {
            // For daily tenants, use the room's user just like monthly tenants
            $users = User::where('name', $request->roomNumber)->first();
            if (!$users) {
                abort(500, 'User for this room not found');
            }
            $tenant->user_id_tenant = $users->id;
            // Set password only if it's a new user or needs updating
            if (!$request->tenantTel) {
                abort(400, 'Tenant phone number is required');
            }
            $users->password = bcrypt($request->tenantTel);
            $users->save();
        }

        $tenant->telNumber = $request->tenantTel;
        $tenant->save(); // บันทึกข้อมูลลงในตาราง

        $meter_reading = MeterReading::where('room_id', $room->roomID)->first();

        if (!$meter_reading) {
            // Create a new meter reading record if one doesn't exist
            $meter_details = MeterDetails::create([
                'water_meter_start' => 0,
                'electricity_meter_start' => 0,
            ]);

            $meter_reading = new MeterReading();
            $meter_reading->room_id = $room->roomID;
            $meter_reading->meter_details_id = $meter_details->meter_detailID;
            $meter_reading->save();
        }

        // Now update the tenant_id
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
        // $request->validate([
        //     'img' => 'required|image|mimes:jpeg,png,jpg,gif',
        // ]);
        //
        // $file = $request->file('img');
        //
        // $filename = $contract_id.'-'.$tenant->tenantID. '.' . $file->getClientOriginalExtension(); // ตั้งชื่อไฟล์ใหม่ (timestamp + นามสกุลเดิม)
        // $path = $file->storeAs('upload', $filename, 'public');
        //
        // $file->store('upload', 'public');
        //
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $file = $request->file('img');

            $filename = $contract_id.'-'.$tenant->tenantID. '.' . $file->getClientOriginalExtension(); // ตั้งชื่อไฟล์ใหม่ (contract_id + tenantID + นามสกุลไฟล์)
            $path = $file->storeAs('upload', $filename, 'public'); // เก็บไฟล์ใน storage/app/public

            $contract->contract_file = $path;
        }

        $contract->save();


        // Create booking with proper field mapping
        $booking = new Booking();
        $booking->tenant_id = $tenant->tenantID;
        $booking->room_id = $room->roomID;
        $booking->booking_type = $request->tenant_type;

        // Ensure check_in and check_out are properly set from checkin and checkout
        $booking->check_in = $request->checkin ?? $request->check_in;
        $booking->check_out = $request->checkout ?? $request->check_out;

        // Set default values for optional fields
        $booking->due_date = $request->due_date ?? null;
        $booking->deposit = $request->deposit ?? 0;
        // Debug information
        \Illuminate\Support\Facades\Log::info('Booking data:', [
            'tenant_id' => $tenant->tenantID,
            'room_id' => $room->roomID,
            'booking_type' => $request->tenant_type,
            'check_in' => $booking->check_in,
            'check_out' => $booking->check_out,
            'due_date' => $booking->due_date,
            'deposit' => $booking->deposit
        ]);

        $booking->save();

        // Check if the user is an admin and redirect accordingly
        if (auth()->check() && auth()->user()->usertype === 'admin') {
            return redirect()->route('admin.daily-tenants')->with('success', 'เพิ่มการจองห้องพักรายวันเรียบร้อยแล้ว');
        }

        // Check if the user is an admin and redirect accordingly
        if (auth()->check() && auth()->user()->usertype === 'admin') {
            return redirect()->route('admin.daily-tenants')->with('success', 'เพิ่มการจองห้องพักรายวันเรียบร้อยแล้ว');
        }

        return redirect('/')->with('success', 'จองห้องพักเรียบร้อยแล้ว');
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
    public function get_history_page(Request $request){
        $telNum = $request->telNum ?? '0';
        $tenant = Tenant::where('telNumber', $telNum)
        ->join('bookings', 'tenants.tenantID', '=', 'bookings.tenant_id')
        ->join('contracts', 'tenants.tenantID', '=', 'contracts.tenant_id')
        ->select('tenants.*', 'bookings.*', 'contracts.*')
        ->orderBy('bookings.created_at','desc')
        ->get();
        return view('history',compact('telNum','tenant'));
    }
}
