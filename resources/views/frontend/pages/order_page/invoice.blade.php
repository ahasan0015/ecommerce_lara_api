<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 40px;
            line-height: 1.5;
        }

        /* Top Header */
        .header-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .company-info {
            width: 50%;
            float: left;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a2a6c; /* Royal Blue */
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-title {
            width: 50%;
            float: right;
            text-align: right;
        }
        .invoice-label {
            font-size: 30px;
            font-weight: bold;
            color: #ddd;
            margin: 0;
            text-transform: uppercase;
        }

        /* Shipping & Order Info Box */
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
            clear: both;
        }
        .delivery-card {
            border-left: 4px solid #1a2a6c;
            background: #f9f9f9;
            padding: 15px;
            width: 55%;
            float: left;
            min-height: 130px;
        }
        .qr-card {
            width: 35%;
            float: right;
            text-align: right;
        }
        .qr-card img {
            width: 110px;
            border: 1px solid #eee;
            padding: 5px;
            background: #fff;
        }

        /* Table Style */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            clear: both;
        }
        .invoice-table th {
            background: #1a2a6c;
            color: #ffffff;
            text-align: left;
            padding: 12px;
            text-transform: uppercase;
            font-size: 11px;
        }
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .invoice-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Summary Section */
        .summary-container {
            width: 100%;
            margin-top: 20px;
        }
        .total-box {
            float: right;
            width: 280px;
        }
        .total-row {
            width: 100%;
            padding: 5px 0;
            display: block;
            clear: both;
        }
        .total-label {
            float: left;
            font-weight: bold;
            color: #666;
        }
        .total-value {
            float: right;
            text-align: right;
        }
        .grand-total {
            border-top: 2px solid #1a2a6c;
            margin-top: 10px;
            padding-top: 10px;
            color: #1a2a6c;
            font-size: 16px;
            font-weight: bold;
        }

        .clearfix { clear: both; }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
            font-size: 10px;
            color: #999;
        }
        .bold { font-weight: bold; color: #000; }
    </style>
</head>

<body>

    <div class="header-container">
        <div class="company-info">
            <div class="company-name">NextFashion Ltd.</div>
            <div style="margin-top: 5px; color: #666;">
                Export Quality Apparel Manufacturer<br>
                Dhaka, Bangladesh | +880 1700-000000<br>
                www.nextfashion.ltd
            </div>
        </div>
        <div class="invoice-title">
            <h1 class="invoice-label">Invoice</h1>
            <div style="margin-top: 10px;">
                <span class="bold">No:</span> #{{ $order->order_number }}<br>
                <span class="bold">Date:</span> {{ $order->created_at->format('d M, Y') }}
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="info-grid">
        <div class="delivery-card">
            <div style="font-weight:bold; color:#1a2a6c; margin-bottom:10px; text-transform:uppercase; font-size:11px; letter-spacing:1px;">
                Shipping To:
            </div>
            <div style="font-size: 14px; line-height: 1.6;">
                <span class="bold">{{ $shipping->name ?? $order->name ?? 'N/A' }}</span><br>
                <span class="bold">Phone:</span> {{ $shipping->phone ?? $order->phone ?? 'N/A' }}<br>
                <span class="bold">Address:</span> {{ $shipping->address ?? $order->address ?? 'N/A' }}<br>
                <span class="bold">Location:</span> {{ $shipping->city ?? $order->city ?? 'Dhaka' }}, Bangladesh
            </div>
        </div>

        <div class="qr-card">
            @if($qrCode)
                <img src="{{ $qrCode }}" alt="QR Tracking">
                <div style="font-size: 9px; margin-top: 8px; font-weight: bold; color:#1a2a6c;">SCAN TO TRACK ORDER</div>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th width="50">Sl.</th>
                <th>Item Description</th>
                <th style="text-align: center;">Price</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>
                        <span class="bold" style="font-size:13px;">{{ $item->variant?->product?->name ?? 'Product' }}</span><br>
                        <small style="color:#666;">Size: {{ $item->variant?->size?->name ?? 'N/A' }} | Color: {{ $item->variant?->color?->name ?? 'N/A' }}</small>
                    </td>
                    <td style="text-align: center;">{{ number_format($item->price, 0) }} TK</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;" class="bold">{{ number_format($item->price * $item->quantity, 0) }} TK</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container">
        <div class="total-box">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-value">{{ number_format($order->subtotal, 0) }} TK</span>
            </div>
            <div class="total-row">
                <span class="total-label">Delivery Charge</span>
                <span class="total-value">{{ number_format($order->total - $order->subtotal, 0) }} TK</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label" style="color:#1a2a6c;">Grand Total</span>
                <span class="total-value">{{ number_format($order->total, 0) }} TK</span>
            </div>
            <div style="text-align: right; font-size: 9px; color: #999; margin-top: 5px;">
                (Total Amount in Taka)
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer">
        <p>
            Thank you for choosing <strong>NextFashion Ltd.</strong><br>
            Exchange policy: Items can be exchanged within 3 days with this original invoice.<br>
            <span style="color:#333; font-weight:bold;">System Developed by: Ahasan Habib Roxy | Full Stack Developer</span>
        </p>
    </div>

</body>
</html>