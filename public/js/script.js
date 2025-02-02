function select_this_room(id) {
    document.getElementById("room_ID_select").innerHTML = id;
    document.getElementById("room_ID_select2").value = id;
}
function daily_form() {
    document.getElementById("monthly_form").style.display = "none";
    document.getElementById("daily_form").style.display = "block";
}
function monthly_form() {
    document.getElementById("daily_form").style.display = "none";
    document.getElementById("monthly_form").style.display = "block";
}
