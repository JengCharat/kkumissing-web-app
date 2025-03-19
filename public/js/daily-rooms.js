function select_this_room(roomId) {
    // Check if the room is already booked monthly (blue button)
    const roomElement = document.querySelector(`button[onclick="select_this_room('${roomId}')"]`);
    if (roomElement && roomElement.classList.contains('bg-blue-500')) {
        alert('ห้องนี้ถูกจองรายเดือนแล้ว ไม่สามารถจองรายวันได้');
        return;
    }

    // Fetch the room details to get the room number
    fetch(`/room-bookings/${roomId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Display the room number in the UI
            document.getElementById("room_ID_select").innerHTML = data.room.roomNumber;
            document.getElementById("selected_room_number").textContent = data.room.roomNumber;

            // Set the room number for the booking form
            document.getElementById("room_ID_select_daily").value = data.room.roomNumber;
            document.getElementById("room_ID_select2").value = data.room.roomNumber;

            // Show room details section
            document.getElementById("room_details").classList.remove("hidden");

            // Display bookings
            displayBookings(data.bookings);
        })
        .catch(error => {
            console.error('Error fetching room details:', error);
        });
}

function displayBookings(bookings) {
    const bookingList = document.getElementById('booking_list');
    bookingList.innerHTML = '';

    if (bookings.length === 0) {
        bookingList.innerHTML = '<p class="text-gray-500">ไม่พบการจองสำหรับห้องนี้</p>';
        return;
    }

    bookings.forEach(booking => {
        const bookingItem = document.createElement('div');
        bookingItem.className = 'p-2 border border-gray-200 rounded';

        const checkIn = new Date(booking.check_in).toLocaleDateString('th-TH');
        const checkOut = new Date(booking.check_out).toLocaleDateString('th-TH');

        // Convert booking type to Thai
        let bookingType = booking.booking_type;
        if (bookingType === 'daily') {
            bookingType = 'รายวัน';
        } else if (bookingType === 'monthly') {
            bookingType = 'รายเดือน';
        }

        bookingItem.innerHTML = `
            <p class="text-sm"><span class="font-medium">รหัสการจอง:</span> ${booking.bookingID}</p>
            <p class="text-sm"><span class="font-medium">ประเภท:</span> ${bookingType}</p>
            <p class="text-sm"><span class="font-medium">ระยะเวลา:</span> ${checkIn} ถึง ${checkOut}</p>
        `;

        bookingList.appendChild(bookingItem);
    });
}

function showBookingForm() {
    // Get the selected room ID
    const selectedRoomId = document.getElementById('room_ID_select2').value;

    // Check if a room is selected
    if (!selectedRoomId) {
        alert('กรุณาเลือกห้องก่อนทำการจอง');
        return;
    }

    // Check if the room is already booked (red or blue)
    const roomElement = document.querySelector(`button[onclick="select_this_room('${selectedRoomId}')"]`);
    if (roomElement && (roomElement.classList.contains('bg-red-500') || roomElement.classList.contains('bg-blue-500'))) {
        alert('ห้องนี้ถูกจองแล้ว ไม่สามารถจองได้');
        return;
    }

    document.getElementById('booking_form').style.display = 'block';

    // Use filter dates if available, otherwise use today and tomorrow
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');

    if (checkInInput && checkInInput.value) {
        document.getElementById('checkin').value = checkInInput.value;
    } else {
        const today = new Date();
        document.getElementById('checkin').value = formatDate(today);
    }

    if (checkOutInput && checkOutInput.value) {
        document.getElementById('checkout').value = checkOutInput.value;
    } else {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('checkout').value = formatDate(tomorrow);
    }
}

function hideBookingForm() {
    document.getElementById('booking_form').style.display = 'none';
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function show_qr() {
    var qrImg = document.getElementById("qr_img");
    if (qrImg.style.display === "none") {
        qrImg.style.display = "block";
    } else {
        qrImg.style.display = "none";
    }
}

function setMinDates() {
    // Get today's date in YYYY-MM-DD format
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const todayFormatted = `${year}-${month}-${day}`;

    // Set min attribute for all date inputs
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = todayFormatted;
    });
}

// Call setMinDates when the document is loaded
document.addEventListener('DOMContentLoaded', setMinDates);
