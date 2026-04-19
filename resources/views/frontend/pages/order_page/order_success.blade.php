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

                <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4 ms-2">
                    Track Your Order
                </a>
            </div>
        </div>
    </div>
@endsection