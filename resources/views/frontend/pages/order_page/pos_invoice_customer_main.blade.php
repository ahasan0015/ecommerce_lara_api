<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 10px; 
            width: 72mm; 
            margin: 0 auto; 
            padding: 8px; 
            color: #000;
        }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        
        /* Shipping Label Style Box */
        .delivery-box { 
            border: 1px solid #000; 
            padding: 5px; 
            margin: 10px 0; 
        }
        .box-title { 
            text-align: center; 
            font-weight: bold; 
            border-bottom: 1px solid #000; 
            margin-bottom: 5px; 
            font-size: 11px;
            text-transform: uppercase;
        }

        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; padding: 3px 0; }
        .items-table td { padding: 4px 0; border-bottom: 0.5px solid #eee; font-size: 9px; }

        .total-section { margin-top: 8px; text-align: right; }
        .qr-section { text-align: center; margin-top: 15px; }
        .qr-section img { width: 100px; height: 100px; border: 1px solid #000; padding: 2px; }
        
        .footer { font-size: 8px; margin-top: 15px; border-top: 1px dashed #000; padding-top: 5px; }
    </style>
</head>
<body>

<div class="header text-center">
    <h2 style="margin:0; font-size: 16px;">RUMA TAILORS</h2>
    <p style="margin:2px;">Dhaka, Bangladesh | Mob: +880 1700-000000</p>
</div>

<div class="divider"></div>

<div class="text-center">
    <span class="bold">INV:</span> #{{ $order->order_number }} | 
    <span class="bold">DATE:</span> {{ $order->created_at->format('d/m/Y') }}
</div>

<div class="delivery-box">
    <div class="box-title">Delivery Information</div>
    
    <div style="margin-bottom: 3px;">
        <span class="bold">Customer:</span> {{ $shipping->name ?? $order->name ?? 'N/A' }}
    </div>
    <div style="margin-bottom: 3px;">
        <span class="bold">Mobile:</span> {{ $shipping->phone ?? $order->phone ?? 'N/A' }}
    </div>
    <div style="margin-bottom: 3px;">
        <span class="bold">Address:</span> {{ $shipping->address ?? $order->address ?? 'N/A' }}
    </div>
    <div>
        <span class="bold">City:</span> {{ $shipping->city ?? $order->city ?? 'Dhaka' }}
    </div>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th width="60%">Item Description</th>
            <th width="10%">Qty</th>
            <th width="30%" style="text-align:right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ Str::limit($item->variant?->product?->name, 20) }}<br>
                <small>Size: {{ $item->variant?->size?->name ?? 'N/A' }}</small>
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td style="text-align:right;">TK {{ number_format($item->price * $item->quantity, 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="total-section">
    <p style="margin:2px;">Subtotal: TK {{ number_format($order->subtotal, 0) }}</p>
    <p style="margin:2px;">Delivery: TK {{ number_format($order->total - $order->subtotal, 0) }}</p>
    <div class="divider" style="width: 50%; margin-left: 50%;"></div>
    <p class="bold" style="font-size: 13px; margin:2px;">NET TOTAL: TK {{ number_format($order->total, 0) }}</p>
</div>

<div class="qr-section">
    @if($qrCode)
        <img src="{{ $qrCode }}" alt="QR">
        <div class="bold" style="font-size: 8px; margin-top: 5px; letter-spacing: 1px;">SCAN TO TRACK ORDER</div>
    @endif
</div>

<div class="footer text-center">
    <p>Thank You For Your Purchase!<br>www.rumatailors.com</p>
    <div style="font-size: 7px; color: #444; margin-top: 5px;">
        System Developed by: <strong>Ahasan Habib Roxy</strong>
    </div>
</div>

</body>
</html>