// Load available rooms when the page loads
document.addEventListener('DOMContentLoaded', function() {
    loadAvailableRooms();
});

function loadAvailableRooms() {
    fetch('/admin/get-available-rooms-daily')
        .then(response => response.json())
        .then(data => {
            const roomSelect = document.getElementById('room_id');
            roomSelect.innerHTML = '<option value="">-- เลือกห้อง --</option>';

            if (data.rooms && data.rooms.length > 0) {
                data.rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.roomNumber;
                    option.textContent = `${room.roomNumber} - ค่าเช่า ${room.daily_rate || '400'} บาท/วัน`;
                    roomSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "ไม่มีห้องว่าง";
                option.disabled = true;
                roomSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error loading available rooms:', error);
            alert('เกิดข้อผิดพลาดในการโหลดข้อมูลห้องว่าง');
        });
}

function showAddBookingForm() {
    document.getElementById('add_booking_modal').classList.remove('hidden');
    // Set default check-in date (today)
    const today = new Date();
    document.getElementById('check_in').value = today.toISOString().split('T')[0];

    // Set default check-out date (tomorrow)
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    document.getElementById('check_out').value = tomorrow.toISOString().split('T')[0];
}

function hideAddBookingForm() {
    document.getElementById('add_booking_modal').classList.add('hidden');
    document.getElementById('add_booking_form').reset();
}

function showEditBookingForm(tenantId, tenantName, tenantTel, checkIn, checkOut) {
    document.getElementById('edit_tenant_id').value = tenantId;
    document.getElementById('edit_tenant_name').value = tenantName;
    document.getElementById('edit_tel_number').value = tenantTel;

    // Format dates for input fields
    if (checkIn) {
        const checkInDate = new Date(checkIn);
        document.getElementById('edit_check_in').value = checkInDate.toISOString().split('T')[0];
    }

    if (checkOut) {
        const checkOutDate = new Date(checkOut);
        document.getElementById('edit_check_out').value = checkOutDate.toISOString().split('T')[0];
    }

    document.getElementById('edit_booking_modal').classList.remove('hidden');
}

function hideEditBookingForm() {
    document.getElementById('edit_booking_modal').classList.add('hidden');
    document.getElementById('edit_booking_form').reset();
}

function showTenantDetails(tenantId) {
    document.getElementById('tenant_details_modal').classList.remove('hidden');

    // Fetch tenant details from the server
    fetch(`/admin/get-daily-tenant-details/${tenantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching tenant details:', data.error);
                return;
            }

            const tenant = data.tenant;
            const booking = data.booking;
            const room = data.room;
            const days = data.days;
            const totalPrice = data.totalPrice;

            // Set tenant details
            document.getElementById('detail_tenant_name').textContent = tenant.tenantName || 'ไม่ระบุ';
            document.getElementById('detail_tenant_tel').textContent = tenant.telNumber || 'ไม่ระบุ';

            // Set room details
            document.getElementById('detail_room_number').textContent = room ? room.roomNumber : 'ไม่ระบุ';
            document.getElementById('detail_room_rate').textContent = room ? ((room.daily_rate || 600) + ' บาท') : 'ไม่ระบุ';

            // Set booking details
            document.getElementById('detail_check_in').textContent = booking && booking.check_in ?
                new Date(booking.check_in).toLocaleDateString('th-TH') : 'ไม่ระบุ';
            document.getElementById('detail_check_out').textContent = booking && booking.check_out ?
                new Date(booking.check_out).toLocaleDateString('th-TH') : 'ไม่ระบุ';

            // Set days and total price
            document.getElementById('detail_days').textContent = days || 0;
            document.getElementById('detail_total_price').textContent = (totalPrice ? totalPrice.toLocaleString() : 0) + ' บาท';

            // Set deposit
            document.getElementById('detail_deposit').textContent = booking && booking.deposit ?
                booking.deposit + ' บาท' : 'ไม่ระบุ';

            // Set status
            let status = 'จองล่วงหน้า';
            if (booking && booking.check_in && booking.check_out) {
                const now = new Date();
                const checkIn = new Date(booking.check_in);
                const checkOut = new Date(booking.check_out);

                if (now < checkIn) {
                    status = 'จองล่วงหน้า';
                } else if (now >= checkIn && now <= checkOut) {
                    status = 'กำลังเข้าพัก';
                } else {
                    status = 'เสร็จสิ้น';
                }
            }
            document.getElementById('detail_status').textContent = status;
        })
        .catch(error => {
            console.error('Error fetching tenant details:', error);
            alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลูกค้า');
        });
}

function hideTenantDetails() {
    document.getElementById('tenant_details_modal').classList.add('hidden');
}

function confirmDeleteBooking(tenantId) {
    document.getElementById('delete_confirmation_modal').classList.remove('hidden');

    // Set up the delete button to perform the actual delete
    document.getElementById('confirm_delete_button').onclick = function() {
        deleteBooking(tenantId);
    };
}

function hideDeleteConfirmation() {
    document.getElementById('delete_confirmation_modal').classList.add('hidden');
}

function deleteBooking(tenantId) {
    // Create a form data object with CSRF token and _method=DELETE
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    // Send delete request to the server using POST method with _method=DELETE
    fetch(`/admin/delete-daily-tenant/${tenantId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Reload the page to show updated tenant list
            window.location.reload();
            alert('ยกเลิกการจองเสร็จสิ้น');
        } else {
            throw new Error('Failed to delete booking');
        }
    })
    .catch(error => {
        console.error('Error deleting booking:', error);
        alert('เกิดข้อผิดพลาดในการยกเลิกการจอง');
        hideDeleteConfirmation();
    });
}
