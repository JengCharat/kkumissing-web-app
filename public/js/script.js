function select_this_room(id) {
    // Fetch the room details to get the room number
    fetch(`/room-bookings/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Display the room number in the UI
            document.getElementById("room_ID_select").innerHTML = data.room.roomNumber;

            // Set the room number (not ID) for the booking forms
            document.getElementById("room_ID_select_monthly").value = data.room.roomNumber;
            document.getElementById("room_ID_select_daily").value = data.room.roomNumber;

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

function daily_form() {
    document.getElementById("monthly_form").style.display = "none";
    document.getElementById("daily_form").style.display = "block";
}
function monthly_form() {
    document.getElementById("daily_form").style.display = "none";
    document.getElementById("monthly_form").style.display = "block";
}
function show_qr() {
    var x = document.getElementById("qr_img_monthly");
    var y = document.getElementById("qr_img_daily");
    if (x.style.display == "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }

    if (y.style.display == "none") {
        y.style.display = "block";
    } else {
        y.style.display = "none";
    }
}
