<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/script.js') }}"> </script>
</head>

<body>
    <h1>firstpage</h1>
    <h2>left room</h2>
    <table border="1">
        <tbody>
            @foreach ($Lrooms->chunk(6) as $room)
            <tr>
                @foreach ($room as $item)
                @if ($item -> status == "Available")
                <td><button style="background: green;" type="button"
                        onclick="select_this_room('{{ $item->roomNumber }}')">{{ $item->roomNumber }}</button></td>
                @else
                <td style="background:red;">{{ $item->roomNumber }}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>right room</h2>
    <table border="1">
        <tbody>
            @foreach ($Rrooms->chunk(6) as $room)
            <tr>
                @foreach ($room as $item)
                @if ($item -> status == "Available")
                <td><button style="background: green;" type="button"
                        onclick="select_this_room('{{ $item->roomNumber }}')">{{ $item->roomNumber }}</button></td>
                @else
                <td style="background:red;">{{ $item->roomNumber }}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>


        <h1 id="room_ID_select"></h1>
        <input type="hidden" name="roomNumber" id="room_ID_select2" value="">
        <button type="button">BOOK NOW</button>
        <br>
        <button type="button" onclick="daily_form()">รายวัน</button>
        <button type="button" onclick="monthly_form()">รายเดือน</button>

        <div id = "monthly_form" class="monthly_form" style = "display:none">
            <h1>monthly</h1>

            <form method="POST" action="/hire" enctype="multipart/form-data">
            @csrf

                <input type="hidden" name="roomNumber" id="room_ID_select_monthly" value="">

                <input type="hidden" name = "tenant_type" value="monthly">
                <h1>deudate</h1>
                <input type="date" name="due_date">
                <h1>deposit</h1>
                <input type="text" name = "deposit">
                <h1>ชื่อผู้เช่า</h1>
                <input type="text" name="tenantName">
                <h1>เบอร์</h1>
                <input type="tel" name="tenantTel">
                <h1>payment</h1>
                <button type="button" onclick = "show_qr()">qr code</button>
                <button type="button">เงินสด</button>
                <img style = "display:none;" src="{{asset('images/rickroll.png')}}" alt="qr img" id = "qr_img_monthly">
                <h1>upload slip</h1>
                <input type="file" name="img">
                <br>
                <button type="submit">finish submit</button>

            </form>
        </div>


        <div id = "daily_form" class="daily_form" style = "display:none">
            <h1>daily</h1>
            <form method="POST" action="/hire" enctype="multipart/form-data">
            @csrf

                <input type="hidden" name="roomNumber" id="room_ID_select_daily" value="">
                <input type="hidden" name = "tenant_type" value="daily">
                <h1>checkin</h1>
                <input type="date" name="checkin">
                <h1>checkout</h1>
                <input type="date" name="checkout">

                <h1>ชื่อผู้เช่า</h1>
                <input type="text" name="tenantName">
                <h1>เบอร์</h1>
                <input type="tel" name="tenantTel">
                <h1>payment</h1>
                <button type="button" onclick = "show_qr()">qr code</button>
                <button type="button">เงินสด</button>

                <img style = "display:none;" src="{{asset('images/rickroll.png')}}" alt="qr img" id = "qr_img_daily">
                <h1>upload slip</h1>
                <input type="file" name = "img">
                <br>
                <button type="submit">finish submit</button>

            </form>
        </div>
</body>

</html>
