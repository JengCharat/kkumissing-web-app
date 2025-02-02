function select_this_room(id) {
    document.getElementById("room_ID_select").innerHTML = id;

    // if (document.getElementById("daily_form").style.display === "block") {
    //     document.getElementById("room_ID_select_daily").value = id;
    // } else if (
    //     document.getElementById("monthly_form").style.display === "block"
    // ) {
    //     document.getElementById("room_ID_select_monthly").value = id;
    // } else {
    document.getElementById("room_ID_select_monthly").value = id;
    document.getElementById("room_ID_select_daily").value = id;
    // }
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
