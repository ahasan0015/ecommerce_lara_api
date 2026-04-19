<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>POS Receipt - {{ $order->order_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.2;
            width: 72mm;
            margin: 0 auto;
            padding: 8px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 9px; }
        
        .info-section { font-size: 10px; margin-bottom: 5px; }
        .info-row { display: block; clear: both; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .items-table th { border-bottom: 1px dashed #000; text-align: left; font-size: 10px; padding-bottom: 4px; }
        .items-table td { padding: 5px 0; vertical-align: top; font-size: 10px; border-bottom: 0.5px solid #eee; }
        
        .col-item { width: 55%; }
        .col-qty { width: 15%; text-align: center; }
        .col-total { width: 30%; text-align: right; }

        .totals-table { width: 100%; margin-top: 8px; }
        .totals-table td { font-size: 11px; padding: 2px 0; }
        .total-amount { font-size: 14px; font-weight: bold; border-top: 1px dashed #000; padding-top: 5px; }

        .qr-section { margin: 15px 0; text-align: center; }
        .qr-section img { width: 110px; height: 110px; border: 1px solid #000; padding: 2px; }
        
        .footer { font-size: 9px; margin-top: 10px; line-height: 1.4; border-top: 1px dashed #000; padding-top: 8px; }
        .dev-info { font-size: 8px; margin-top: 10px; color: #333; font-style: italic; }
    </style>
</head>
<body>

<div class="header text-center">
    <h2>RUMA TAILORS</h2>
    <p>Dhaka, Bangladesh<br>
    Mob: +880 1700-000000</p>
</div>

<div class="divider"></div>

<div class="info-section">
    <div class="info-row">Order: #{{ $order->order_number }}</div>
    <div class="info-row">Date : {{ $order->created_at->format('d/M/y h:i A') }}</div>
    <div class="info-row">Cust : {{ $order->name ?? ($order->user ? $order->user->name : 'Walk-in Customer') }}</div>
    <div class="info-row bold">Mob  : {{ $order->phone ?? ($order->user ? $order->user->phone : 'N/A') }}</div>
</div>

<div class="divider"></div>

<table class="items-table">
    <thead>
        <tr>
            <th class="col-item">Item Description</th>
            <th class="col-qty">Qty</th>
            <th class="col-total">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td class="col-item">
                {{ Str::limit($item->variant?->product?->name ?? 'Product', 22) }}<br>
                <small>Size: {{ $item->variant?->size?->name ?? 'N/A' }} | {{ $item->variant?->color?->name ?? '' }}</small>
            </td>
            <td class="col-qty">{{ $item->quantity }}</td>
            <td class="col-total">TK {{ number_format($item->price * $item->quantity, 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals-table">
    <tr>
        <td class="text-right">Subtotal:</td>
        <td class="text-right">TK {{ number_format($order->subtotal, 0) }}</td>
    </tr>
    <tr>
        <td class="text-right">Delivery Charge:</td>
        <td class="text-right">TK {{ number_format($order->total - $order->subtotal, 0) }}</td>
    </tr>
    <tr class="total-amount">
        <td class="text-right">NET PAYABLE:</td>
        <td class="text-right"> {{ number_format($order->total, 0) }} Taka</td>
    </tr>
</table>

<div class="qr-section">
    @if($qrCode)
        <img src="{{ $qrCode }}" alt="QR Code">
    @else
        <div style="border: 1px solid #ccc; padding: 10px; font-size: 8px;">QR Code Placeholder</div>
    @endif
    <p style="font-size: 8px; margin-top: 5px;">Scan to Track Your Order Status</p>
</div>

<div class="footer text-center">
    <p>Thank You For Shopping With Us!<br>
    Visit: www.rumatailors.com<br>
    <strong>Return Policy:</strong> 3 Days exchange with this receipt.</p>
    
    <div class="dev-info">
        System Developed by: <strong>Ahasan Habib Roxy</strong><br>
        Full Stack Developer
    </div>
</div>

</body>
</html>