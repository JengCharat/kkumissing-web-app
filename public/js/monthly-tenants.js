// Load available rooms when the page loads
document.addEventListener('DOMContentLoaded', function() {
    loadAvailableRooms();
});

function loadAvailableRooms() {
    fetch('/admin/get-available-rooms')
        .then(response => response.json())
        .then(data => {
            const roomSelect = document.getElementById('room_id');
            roomSelect.innerHTML = '<option value="">-- เลือกห้อง --</option>';

            if (data.rooms && data.rooms.length > 0) {
                data.rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.roomNumber;
                    option.textContent = `${room.roomNumber} - ค่าเช่า ${room.month_rate} บาท/เดือน`;
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

function showAddTenantForm() {
    document.getElementById('add_tenant_modal').classList.remove('hidden');
    // Set default due date (1 month from today)
    const today = new Date();
    const nextMonth = new Date(today);
    nextMonth.setMonth(today.getMonth() + 1);

    document.getElementById('due_date').value = nextMonth.toISOString().split('T')[0];
}

function hideAddTenantForm() {
    document.getElementById('add_tenant_modal').classList.add('hidden');
    document.getElementById('add_tenant_form').reset();
}

function showEditTenantForm(tenantId, tenantName, tenantTel) {
    document.getElementById('edit_tenant_id').value = tenantId;
    document.getElementById('edit_tenant_name').value = tenantName;
    document.getElementById('edit_tel_number').value = tenantTel;
    document.getElementById('edit_tenant_modal').classList.remove('hidden');
}

function hideEditTenantForm() {
    document.getElementById('edit_tenant_modal').classList.add('hidden');
    document.getElementById('edit_tenant_form').reset();
}

function showTenantDetails(tenantId) {
    document.getElementById('tenant_details_modal').classList.remove('hidden');

    // Fetch tenant details from the server
    fetch(`/admin/get-tenant-details/${tenantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching tenant details:', data.error);
                return;
            }

            const tenant = data.tenant;
            const booking = data.booking;
            const room = data.room;
            const contract = data.contract;

            // Set tenant details
            document.getElementById('detail_tenant_name').textContent = tenant.tenantName || 'ไม่ระบุ';
            document.getElementById('detail_tenant_tel').textContent = tenant.telNumber || 'ไม่ระบุ';

            // Set room details
            document.getElementById('detail_room_number').textContent = room ? room.roomNumber : 'ไม่ระบุ';
            document.getElementById('detail_room_rate').textContent = room ? (room.month_rate + ' บาท') : 'ไม่ระบุ';

            // Set booking details
            document.getElementById('detail_check_in').textContent = booking && booking.check_in ?
                new Date(booking.check_in).toLocaleDateString('th-TH') : 'ไม่ระบุ';
            document.getElementById('detail_due_date').textContent = booking && booking.due_date ?
                new Date(booking.due_date).toLocaleDateString('th-TH') : 'ไม่ระบุ';

            // Set deposit
            document.getElementById('detail_deposit').textContent = booking && booking.deposit ?
                booking.deposit + ' บาท' : 'ไม่ระบุ';

            // Set status
            document.getElementById('detail_status').textContent = room ? 'อยู่ระหว่างเช่า' : 'ไม่ได้เช่าห้อง';

            // Set contract file
            const contractFileImage = document.getElementById('contract_file_image');
            const noContractFile = document.getElementById('no_contract_file');

            if (contract && contract.contract_file) {
                // The contract_file path is already relative to storage
                contractFileImage.src = `/storage/${contract.contract_file}`;
                contractFileImage.classList.remove('hidden');
                noContractFile.classList.add('hidden');
            } else {
                contractFileImage.classList.add('hidden');
                noContractFile.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error fetching tenant details:', error);
            alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลูกค้า');
        });
}

function hideTenantDetails() {
    document.getElementById('tenant_details_modal').classList.add('hidden');
}

function confirmDeleteTenant(tenantId) {
    document.getElementById('delete_confirmation_modal').classList.remove('hidden');

    // Set up the delete button to perform the actual delete
    document.getElementById('confirm_delete_button').onclick = function() {
        deleteTenant(tenantId);
    };
}

function hideDeleteConfirmation() {
    document.getElementById('delete_confirmation_modal').classList.add('hidden');
}

function deleteTenant(tenantId) {
    // Create a form data object with CSRF token and _method=DELETE
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    // Send delete request to the server using POST method with _method=DELETE
    fetch(`/admin/delete-monthly-tenant/${tenantId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Show alert first, then reload the page
            alert('ทำการย้ายออกเสร็จสิ้น');
            window.location.reload();
        } else {
            return response.text().then(text => {
                throw new Error(text || 'Failed to delete tenant');
            });
        }
    })
    .catch(error => {
        console.error('Error deleting tenant:', error);
        alert('เกิดข้อผิดพลาดในการแจ้งย้ายออก');
        hideDeleteConfirmation();
    });
}
