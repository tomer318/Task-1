<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Bán Hàng - TechZone</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #e11d48;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            color: #e11d48;
            margin: 0;
        }
        .brand-sub {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-title {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td {
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 11px;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-box {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-box td {
            padding: 6px 8px;
            font-size: 12px;
        }
        .total-box .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #e11d48;
            border-top: 2px solid #cbd5e1;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            border-top: 1px dashed #cbd5e1;
            padding-top: 15px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            background: #e2e8f0;
            color: #334155;
        }
    </style>
</head>
<body>

    <!-- Header hóa đơn -->
    <table class="header">
        <tr>
            <td style="width: 60%;">
                <h1 class="brand-title">TECHZONE</h1>
                <div class="brand-sub">Siêu Thị Điện Máy & Thiết Bị Công Nghệ 2026</div>
                <div style="margin-top: 6px; font-size: 11px; color: #475569;">
                    Website: techzone-storefront.onrender.com <br>
                    Hotline: 1900 8888 • Email: support@techzone.vn
                </div>
            </td>
            <td style="width: 40%;" class="text-right">
                <div class="invoice-title">HÓA ĐƠN MUA HÀNG</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Mã đơn: <strong>#{{ $order->order_code ?? $order->id }}</strong><br>
                    Ngày lập: {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    Thanh toán: <span class="badge">{{ $order->payment_status ?? 'Đã xác nhận' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Thông tin khách hàng -->
    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong style="color: #0f172a;">Khách hàng nhận:</strong><br>
                Họ và tên: {{ $order->user->name ?? $order->shipping_name ?? 'Khách hàng TechZone' }}<br>
                Số điện thoại: {{ $order->shipping_phone ?? 'Chưa cập nhật' }}<br>
                Địa chỉ giao hàng: {{ $order->shipping_address ?? 'Nhận tại cửa hàng' }}
            </td>
            <td style="width: 50%;" class="text-right">
                <strong style="color: #0f172a;">Phương thức thanh toán:</strong><br>
                {{ strtoupper($order->payment_method ?? 'COD') }}<br>
                Hình thức vận chuyển: Giao hàng tiêu chuẩn TechZone
            </td>
        </tr>
    </table>

    <!-- Bảng sản phẩm -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">#</th>
                <th>Tên thiết bị / sản phẩm</th>
                <th class="text-center" style="width: 70px;">Số lượng</th>
                <th class="text-right" style="width: 110px;">Đơn giá</th>
                <th class="text-right" style="width: 120px;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Sản phẩm TechZone' }}</strong>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td class="text-right"><strong>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tổng kết thanh toán -->
    <table class="total-box">
        <tr>
            <td class="text-right">Tổng tiền hàng:</td>
            <td class="text-right">{{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}₫</td>
        </tr>
        @if(isset($order->discount_amount) && $order->discount_amount > 0)
        <tr>
            <td class="text-right">Giảm giá voucher:</td>
            <td class="text-right" style="color: #e11d48;">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
        </tr>
        @endif
        @if(isset($order->shipping_fee))
        <tr>
            <td class="text-right">Phí vận chuyển:</td>
            <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td class="text-right">Tổng thanh toán:</td>
            <td class="text-right">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
        </tr>
    </table>

    <!-- Lời cảm ơn chân trang -->
    <div class="footer">
        Cảm ơn quý khách đã tin tưởng và đồng hành cùng hệ thống bán lẻ công nghệ <strong>TechZone</strong>!<br>
        Mọi thắc mắc về bảo hành hoặc đổi trả trong vòng 30 ngày, quý khách vui lòng liên hệ tổng đài 1900 8888.
    </div>

</body>
</html>