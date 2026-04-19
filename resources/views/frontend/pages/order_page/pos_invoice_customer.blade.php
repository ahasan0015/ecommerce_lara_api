<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; width: 72mm; margin: 0 auto; padding: 5px; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .delivery-box { border: 1px solid #000; padding: 5px; margin: 5px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; }
        .items-table td { padding: 3px 0; border-bottom: 0.5px solid #eee; }
        .qr-section { text-align: center; margin-top: 10px; }
        .qr-section img { width: 100px; height: 100px; border: 1px solid #000; padding: 2px; }
        .dev-info { font-size: 8px; margin-top: 10px; border-top: 0.5px solid #ccc; padding-top: 3px; color: #444; }
    </style>
</head>
<body>

<div class="header text-center">
    <h2 style="margin:0;">RUMA TAILORS</h2>
    <p style="margin:2px;">Dhaka, Bangladesh | +880 1700-000000</p>
</div>

<div style="border-top: 1px dashed #000; margin: 5px 0;"></div>

<div class="delivery-box">
    <div style="text-align:center; border-bottom:1px solid #000; margin-bottom:5px; font-weight:bold;">DELIVERY INFORMATION</div>
    
    {{-- অর্ডারের সরাসরি ডাটা অথবা শিপিং অ্যাড্রেস টেবিল থেকে ডাটা নেওয়া হচ্ছে --}}
    <div class="label-row"><span class="bold">Name:</span> {{ $order->shippingAddress->name ?? $order->name ?? 'N/A' }}</div>
    <div class="label-row"><span class="bold">Mobile:</span> {{ $order->shippingAddress->phone ?? $order->phone ?? 'N/A' }}</div>
    <div class="label-row"><span class="bold">Address:</span> {{ $order->shippingAddress->address ?? $order->address ?? 'N/A' }}</div>
    <div class="label-row"><span class="bold">City:</span> {{ $order->shippingAddress->city ?? $order->city ?? 'Dhaka' }}</div>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th width="60%">Item</th>
            <th width="10%">Qty</th>
            <th width="30%" style="text-align:right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ Str::limit($item->variant?->product?->name, 20) }}<br><small>({{ $item->variant?->size?->name ?? 'N/A' }})</small></td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td style="text-align:right;">TK {{ number_format($item->price * $item->quantity, 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 5px; text-align:right;">
    <p style="margin:2px;">Subtotal: TK {{ number_format($order->subtotal, 0) }}</p>
    <p style="margin:2px;">Delivery: TK {{ number_format($order->total - $order->subtotal, 0) }}</p>
    <p class="bold" style="font-size:12px; margin:2px;">NET PAYABLE: TK {{ number_format($order->total, 0) }}</p>
</div>

<div class="qr-section">
    @if($qrCode)
        <img src="{{ $qrCode }}" alt="QR Tracking">
        <div class="bold" style="font-size: 8px; margin-top: 3px;">SCAN TO TRACK ORDER</div>
    @else
        <p>QR Code Error</p>
    @endif
</div>

<div class="footer text-center" style="margin-top:10px;">
    <p>Thank You For Your Purchase!<br>www.rumatailors.com</p>
    
    <div class="dev-info">
        Developed by: <strong>Ahasan Habib Roxy</strong><br>
        Full Stack Developer
    </div>
</div>

</body>
</html>