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
