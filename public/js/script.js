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
    // Check if a room has been selected
    const selectedRoom = document.getElementById("room_ID_select").textContent.trim();
    if (!selectedRoom) {
        // If no room is selected, show an alert
        alert("กรุณาเลือกห้องก่อนทำการจอง");
        return;
    }

    // Check if dates have been selected in the filter
    const filterCheckin = document.getElementById("filter_checkin").value;
    const filterCheckout = document.getElementById("filter_checkout").value;

    if (!filterCheckin || !filterCheckout) {
        // If dates are not selected, show an alert
        alert("กรุณาเลือกวันที่เช็คอินและเช็คเอาท์ก่อนทำการจอง");
        return;
    }

    // If a room is selected and dates are set, proceed with showing the form
    document.getElementById("monthly_form").style.display = "none";
    document.getElementById("daily_form").style.display = "block";

    // Display the room number in the daily booking form
    document.getElementById("room_display_daily").textContent = selectedRoom;

    // Set the check-in and check-out dates from the filter to the booking form
    document.getElementById("daily_checkin").value = filterCheckin;
    document.getElementById("daily_checkout").value = filterCheckout;

    // Calculate days and price based on the selected dates
    calculateDaysAndPrice();
}
// function monthly_form() {
//     // Check if a room has been selected
//     const selectedRoom = document.getElementById("room_ID_select").textContent.trim();
//     if (!selectedRoom) {
//         // If no room is selected, show an alert
//         alert("กรุณาเลือกห้องก่อนทำการจอง");
//         return;
//     }

//     // If a room is selected, proceed with showing the form
//     document.getElementById("daily_form").style.display = "none";
//     document.getElementById("monthly_form").style.display = "block";

//     // Display the room number in the monthly booking form
//     document.getElementById("room_display_monthly").textContent = selectedRoom;
// }
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

// Function to set minimum date for all date inputs to today
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

// Function to calculate days and price for daily booking
function calculateDaysAndPrice() {
    const checkinInput = document.getElementById('daily_checkin');
    const checkoutInput = document.getElementById('daily_checkout');
    const daysCountElement = document.getElementById('days_count');
    const totalPriceElement = document.getElementById('total_price');

    if (checkinInput.value && checkoutInput.value) {
        const checkinDate = new Date(checkinInput.value);
        const checkoutDate = new Date(checkoutInput.value);

        // Calculate the difference in days
        const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (daysDiff > 0) {
            const dailyRate = 400; // Default rate
            const totalPrice = dailyRate * daysDiff;

            daysCountElement.textContent = daysDiff + ' วัน';
            totalPriceElement.textContent = totalPrice + ' บาท';
        } else {
            daysCountElement.textContent = 'โปรดเลือกวันที่ให้ถูกต้อง';
            totalPriceElement.textContent = '-';
        }
    } else {
        daysCountElement.textContent = '-';
        totalPriceElement.textContent = '-';
    }
}

// Function to validate the date filter form
function validateDateFilter() {
    const checkinInput = document.getElementById('filter_checkin');
    const checkoutInput = document.getElementById('filter_checkout');

    if (!checkinInput.value || !checkoutInput.value) {
        alert("กรุณาเลือกวันที่เช็คอินและเช็คเอาท์ก่อนกรอง");
        return false;
    }

    const checkinDate = new Date(checkinInput.value);
    const checkoutDate = new Date(checkoutInput.value);

    if (checkoutDate <= checkinDate) {
        alert("วันที่เช็คเอาท์ต้องมากกว่าวันที่เช็คอิน");
        return false;
    }

    return true;
}

// Call setMinDates when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    setMinDates();

    // Initialize days and price calculation if dates are already set
    if (document.getElementById('daily_checkin') && document.getElementById('daily_checkout')) {
        calculateDaysAndPrice();
    }
});
