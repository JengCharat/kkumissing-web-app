<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน - {{ $bill->BillNo }}</title>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .receipt-header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .receipt-info div {
            flex: 1;
        }
        .receipt-info h2 {
            font-size: 16px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .receipt-info p {
            margin: 5px 0;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .receipt-table th {
            background-color: #f5f5f5;
        }
        .receipt-total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }
        .receipt-footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature div {
            flex: 1;
            text-align: center;
            padding: 10px;
        }
        .signature .line {
            width: 80%;
            margin: 40px auto 10px;
            border-top: 1px solid #333;
        }
        @media print {
            body {
                padding: 0;
                background-color: white;
            }
            .receipt {
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>ใบเสร็จรับเงิน / RECEIPT</h1>
            <p>วราภรณ์ แมนชั่น</p>
            <p>123 ถนนสุขุมวิท กรุงเทพฯ 10110</p>
            <p>โทร: 02-123-4567</p>
        </div>

        <div class="receipt-info">
            <div>
                <h2>ข้อมูลลูกค้า / Customer Information</h2>
                <p><strong>ชื่อ / Name:</strong> {{ $bill->tenant->tenantName }}</p>
                <p><strong>เบอร์โทร / Tel:</strong> {{ $bill->tenant->telNumber }}</p>
                <p><strong>ห้อง / Room:</strong> {{ $bill->room->roomNumber }}</p>
            </div>
            <div>
                <h2>ข้อมูลใบเสร็จ / Receipt Information</h2>
                <p><strong>เลขที่ / No.:</strong> {{ $bill->BillNo }}</p>
                <p><strong>วันที่ออกบิล / Bill Date:</strong> {{ date('d/m/Y', strtotime($bill->BillDate)) }}</p>
                <p><strong>วันที่ชำระเงิน / Payment Date:</strong> {{ date('d/m/Y', strtotime($bill->updated_at)) }}</p>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>รายการ / Description</th>
                    <th>จำนวนเงิน / Amount (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ค่าห้องพัก / Room Rate</td>
                    <td>{{ number_format($bill->room_rate ?? ($bill->total_price - ($bill->water_price + $bill->electricity_price + $bill->damage_fee + $bill->overdue_fee)), 2) }}</td>
                </tr>
                <tr>
                    <td>ค่าน้ำ / Water</td>
                    <td>{{ number_format($bill->water_price, 2) }}</td>
                </tr>
                <tr>
                    <td>ค่าไฟฟ้า / Electricity</td>
                    <td>{{ number_format($bill->electricity_price, 2) }}</td>
                </tr>
                {{-- @if($bill->damage_fee > 0) --}}
                <tr>
                    <td>ค่าเสียหาย / Damage Fee</td>
                    <td>{{ number_format($bill->damage_fee, 2) }}</td>
                </tr>
                {{-- @endif --}}
                {{-- @if($bill->overdue_fee > 0) --}}
                <tr>
                    <td>ค่าปรับล่าช้า / Overdue Fee</td>
                    <td>{{ number_format($bill->overdue_fee, 2) }}</td>
                </tr>
                {{-- @endif --}}
            </tbody>
            <tfoot>
                <tr>
                    <th>รวมทั้งสิ้น / Total</th>
                    <th>{{ number_format($bill->total_price, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="receipt-total">
            <p>จำนวนเงินรวมทั้งสิ้น / Total Amount: {{ number_format($bill->total_price, 2) }} บาท</p>
            <p>{{ baht_text($bill->total_price) }}</p>
        </div>

        <div class="signature">
            <div>
                <div class="line"></div>
                <p>ลายเซ็นผู้รับเงิน / Receiver's Signature</p>
            </div>
            <div>
                <div class="line"></div>
                <p>ลายเซ็นผู้จ่ายเงิน / Payer's Signature</p>
            </div>
        </div>

        <div class="receipt-footer">
            <p>ขอบคุณที่ใช้บริการ / Thank you for your business</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">พิมพ์ใบเสร็จ / Print Receipt</button>
        <button onclick="window.close();" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">ปิด / Close</button>
    </div>
</body>
</html>
