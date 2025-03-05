/**
 * Monthly Rooms JavaScript Functions
 */

function select_this_room(roomID, roomNumber) {
    // Show room details section
    document.getElementById('room_details').style.display = 'block';
    document.getElementById('selected_room_number').textContent = roomNumber;

    // Store roomID in a hidden field for form submission
    if (!document.getElementById('room_id')) {
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.id = 'room_id';
        hiddenField.name = 'roomID';
        hiddenField.value = roomID;
        document.getElementById('bill_form').appendChild(hiddenField);
    } else {
        document.getElementById('room_id').value = roomID;
    }

    // Fetch utility rates
    fetch('/admin/utilities')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const waterRate = doc.getElementById('unit_price_water').value;
            const electricityRate = doc.getElementById('unit_price_electricity').value;

            document.getElementById('water_rate').value = waterRate;
            document.getElementById('electricity_rate').value = electricityRate;
        })
        .catch(error => {
            console.error('Error fetching utility rates:', error);
        });

    // Fetch tenant details for this room using AJAX
    fetch(`/admin/get-room-tenant/${roomID}`)
        .then(response => response.json())
        .then(data => {
            if (data.tenant) {
                document.getElementById('tenant_name').textContent = data.tenant.tenantName;
                document.getElementById('tenant_phone').textContent = data.tenant.telNumber;

                // Store tenantID in a hidden field for form submission
                if (!document.getElementById('tenant_id')) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.id = 'tenant_id';
                    hiddenField.name = 'tenantID';
                    hiddenField.value = data.tenant.tenantID;
                    document.getElementById('bill_form').appendChild(hiddenField);
                } else {
                    document.getElementById('tenant_id').value = data.tenant.tenantID;
                }
            } else {
                document.getElementById('tenant_name').textContent = 'ไม่พบข้อมูลผู้เช่า';
                document.getElementById('tenant_phone').textContent = '-';
            }

            if (data.booking) {
                document.getElementById('rent_start_date').textContent = new Date(data.booking.check_in).toLocaleDateString('th-TH');
            } else {
                document.getElementById('rent_start_date').textContent = '-';
            }

            if (data.room) {
                document.getElementById('room_price').textContent = data.room.month_rate.toLocaleString();
                // Set the room rate in the form field
                document.getElementById('room_rate').value = data.room.month_rate;
            } else {
                document.getElementById('room_price').textContent = '-';
                document.getElementById('room_rate').value = 0;
            }

            // Load existing bills for this room
            loadBills(roomID);
        })
        .catch(error => {
            console.error('Error fetching room details:', error);
            alert('เกิดข้อผิดพลาดในการดึงข้อมูลห้อง');
        });

    // Set current month and year
    const now = new Date();
    document.getElementById('billing_month').value = now.getMonth() + 1;
    document.getElementById('billing_year').value = now.getFullYear();
}

function loadBills(roomID) {
    fetch(`/admin/get-room-bills/${roomID}`)
        .then(response => response.json())
        .then(data => {
            const billsTable = document.getElementById('bills_table_body');
            billsTable.innerHTML = '';

            if (data.bills && data.bills.length > 0) {
                data.bills.forEach(bill => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2 border">${bill.BillNo}</td>
                        <td class="px-4 py-2 border">${new Date(bill.BillDate).toLocaleDateString('th-TH')}</td>
                        <td class="px-4 py-2 border">${bill.water_price ? bill.water_price.toLocaleString() : '0'}</td>
                        <td class="px-4 py-2 border">${bill.electricity_price ? bill.electricity_price.toLocaleString() : '0'}</td>
                        <td class="px-4 py-2 border">${bill.damage_fee ? bill.damage_fee.toLocaleString() : '0'}</td>
                        <td class="px-4 py-2 border">${bill.overdue_fee ? bill.overdue_fee.toLocaleString() : '0'}</td>
                        <td class="px-4 py-2 border">${bill.total_price ? bill.total_price.toLocaleString() : '0'}</td>
                        <td class="px-4 py-2 border">
                            <button onclick="editBill(${bill.BillNo})" class="text-blue-500 hover:text-blue-700 mr-2">แก้ไข</button>
                            <button onclick="deleteBill(${bill.BillNo})" class="text-red-500 hover:text-red-700">ลบ</button>
                        </td>
                    `;
                    billsTable.appendChild(row);
                });
                document.getElementById('bills_table').style.display = 'table';
                document.getElementById('no_bills_message').style.display = 'none';
            } else {
                document.getElementById('bills_table').style.display = 'none';
                document.getElementById('no_bills_message').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching bills:', error);
            alert('เกิดข้อผิดพลาดในการดึงข้อมูลบิล');
        });
}

function calculateWaterUnits() {
    const waterMeterStart = parseFloat(document.getElementById('water_meter_start').value) || 0;
    const waterMeterEnd = parseFloat(document.getElementById('water_meter_end').value) || 0;
    const waterRate = parseFloat(document.getElementById('water_rate').value) || 0;

    if (waterMeterEnd >= waterMeterStart) {
        const waterUnits = waterMeterEnd - waterMeterStart;
        const waterTotal = waterUnits * waterRate;

        document.getElementById('water_units').value = waterUnits.toFixed(2);
        document.getElementById('water_total').value = waterTotal.toFixed(2);

        calculateTotal();
    }
}

function calculateElectricityUnits() {
    const electricityMeterStart = parseFloat(document.getElementById('electricity_meter_start').value) || 0;
    const electricityMeterEnd = parseFloat(document.getElementById('electricity_meter_end').value) || 0;
    const electricityRate = parseFloat(document.getElementById('electricity_rate').value) || 0;

    if (electricityMeterEnd >= electricityMeterStart) {
        const electricityUnits = electricityMeterEnd - electricityMeterStart;
        const electricityTotal = electricityUnits * electricityRate;

        document.getElementById('electricity_units').value = electricityUnits.toFixed(2);
        document.getElementById('electricity_total').value = electricityTotal.toFixed(2);

        calculateTotal();
    }
}

function calculateTotal() {
    const waterTotal = parseFloat(document.getElementById('water_total').value) || 0;
    const electricityTotal = parseFloat(document.getElementById('electricity_total').value) || 0;
    const roomRate = parseFloat(document.getElementById('room_rate').value) || 0;
    const damageFee = parseFloat(document.getElementById('damage_fee').value) || 0;
    const overdueFee = parseFloat(document.getElementById('overdue_fee').value) || 0;

    const total = roomRate + waterTotal + electricityTotal + damageFee + overdueFee;
    document.getElementById('total_price').value = total.toFixed(2);
}

function editBill(billId) {
    fetch(`/admin/get-bill/${billId}`)
        .then(response => response.json())
        .then(data => {
            if (data.bill) {
                // Populate form with bill data
                const billDate = new Date(data.bill.BillDate);
                document.getElementById('billing_month').value = billDate.getMonth() + 1;
                document.getElementById('billing_year').value = billDate.getFullYear();

                // Get meter readings for this bill if available
                const roomId = data.bill.roomID;
                fetch(`/admin/get-room-tenant/${roomId}`)
                    .then(response => response.json())
                    .then(roomData => {
                        if (roomData.meter_readings && roomData.meter_readings.meterdetails) {
                            const meterDetails = roomData.meter_readings.meterdetails;
                            document.getElementById('water_meter_start').value = meterDetails.water_meter_start || '0';
                            document.getElementById('water_meter_end').value = meterDetails.water_meter_end || '0';
                            document.getElementById('electricity_meter_start').value = meterDetails.electricity_meter_start || '0';
                            document.getElementById('electricity_meter_end').value = meterDetails.electricity_meter_end || '0';

                            // Calculate units
                            calculateWaterUnits();
                            calculateElectricityUnits();
                        } else {
                            // If no meter details, use the units from the bill
                            document.getElementById('water_units').value = data.water_units || '';
                            document.getElementById('electricity_units').value = data.electricity_units || '';

                            // Calculate totals
                            const waterRate = parseFloat(document.getElementById('water_rate').value) || 0;
                            const electricityRate = parseFloat(document.getElementById('electricity_rate').value) || 0;

                            document.getElementById('water_total').value = data.bill.water_price || '0';
                            document.getElementById('electricity_total').value = data.bill.electricity_price || '0';

                            // Estimate meter readings if needed
                            if (data.water_units && waterRate > 0) {
                                document.getElementById('water_meter_start').value = '0';
                                document.getElementById('water_meter_end').value = data.water_units;
                            }

                            if (data.electricity_units && electricityRate > 0) {
                                document.getElementById('electricity_meter_start').value = '0';
                                document.getElementById('electricity_meter_end').value = data.electricity_units;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching room details:', error);
                    });

                document.getElementById('damage_fee').value = data.bill.damage_fee || '0';
                document.getElementById('overdue_fee').value = data.bill.overdue_fee || '0';
                document.getElementById('total_price').value = data.bill.total_price || '0';

                // Add bill ID to form for update
                if (!document.getElementById('bill_id')) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.id = 'bill_id';
                    hiddenField.name = 'billId';
                    hiddenField.value = billId;
                    document.getElementById('bill_form').appendChild(hiddenField);
                } else {
                    document.getElementById('bill_id').value = billId;
                }

                // Change button text
                document.getElementById('submit_button').textContent = 'อัพเดทบิล';

                // Scroll to form
                document.getElementById('bill_form').scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error fetching bill details:', error);
            alert('เกิดข้อผิดพลาดในการดึงข้อมูลบิล');
        });
}

function deleteBill(billId) {
    if (confirm('คุณต้องการลบบิลนี้ใช่หรือไม่?')) {
        fetch(`/admin/delete-bill/${billId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('ลบบิลเรียบร้อยแล้ว');
                // Reload bills
                loadBills(document.getElementById('room_id').value);
            } else {
                alert('เกิดข้อผิดพลาดในการลบบิล: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting bill:', error);
            alert('เกิดข้อผิดพลาดในการลบบิล');
        });
    }
}

function resetForm() {
    document.getElementById('bill_form').reset();
    if (document.getElementById('bill_id')) {
        document.getElementById('bill_id').remove();
    }
    document.getElementById('submit_button').textContent = 'บันทึก';

    // Set current month and year
    const now = new Date();
    document.getElementById('billing_month').value = now.getMonth() + 1;
    document.getElementById('billing_year').value = now.getFullYear();

    // Fetch utility rates again
    fetch('/admin/utilities')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const waterRate = doc.getElementById('unit_price_water').value;
            const electricityRate = doc.getElementById('unit_price_electricity').value;

            document.getElementById('water_rate').value = waterRate;
            document.getElementById('electricity_rate').value = electricityRate;
        })
        .catch(error => {
            console.error('Error fetching utility rates:', error);
        });
}
