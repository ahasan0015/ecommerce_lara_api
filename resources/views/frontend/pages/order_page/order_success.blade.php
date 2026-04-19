@extends('frontend.layout.master')

@section('content')
    <div class="container my-5 text-center">
        <div class="card p-5 shadow-sm border-0">
            <div class="mb-4">
                <i class="fas fa-shopping-bag fa-4x text-primary"></i>
            </div>
            <h2 class="fw-bold">Thank you, your order has been confirmed!</h2>

            <p class="text-muted">
                Your Order Number: <strong>#{{ $order->order_number }}</strong>
            </p>

            <p>We will contact you shortly.</p>

            <div class="mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill px-4">
                    Continue Shopping
                </a>

                <div class="mt-4">
                    {{-- <a href="{{ route('order.invoice.download_main', $order->order_number) }}" --}}
                    <a href="{{ route('order.invoice.download_invoice', $order->order_number) }}"
                        class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-file-pdf me-2"></i> Download Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection