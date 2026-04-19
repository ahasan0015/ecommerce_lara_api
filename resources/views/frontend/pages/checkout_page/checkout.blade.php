@extends('frontend.layout.master')

@section('title', 'Checkout')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/check_out.css') }}">
@endsection

@section('content')
    <div class="container my-5">
        <div class="row g-5">
            <div class="col-md-7 col-lg-8">
                <div class="card checkout-card p-4">
                    <h4 class="mb-4 fw-bold"><i class="fas fa-truck me-2"></i>Shipping Address</h4>

                    <form action="#" method="GET">
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter your full name" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">+88</span>
                                    <input type="tel" class="form-control" placeholder="017XXXXXXXX" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Email <span
                                        class="text-muted small">(Optional)</span></label>
                                <input type="email" class="form-control" placeholder="you@example.com">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Full Address</label>
                                <textarea class="form-control" rows="3" placeholder="House no, Road no, Area..."
                                    required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City</label>
                                <select class="form-select" required>
                                    <option value="">Choose...</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chittagong">Chittagong</option>
                                    <option value="Sylhet">Sylhet</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-5">

                        <h4 class="mb-4 fw-bold"><i class="fas fa-credit-card me-2"></i>Payment Method</h4>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-check border p-3 rounded mb-3 payment-option">
                                    <input id="cod" name="paymentMethod" type="radio" class="form-check-input" checked
                                        required>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="cod">
                                        <span>Cash on Delivery</span>
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                    </label>
                                </div>

                                <div class="form-check border p-3 rounded payment-option opacity-50">
                                    <input id="online" name="paymentMethod" type="radio" class="form-check-input" disabled>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="online">
                                        <span>Online Payment (bKash/Nagad)</span>
                                        <span class="badge bg-secondary">Coming Soon</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button class="w-100 btn btn-primary btn-lg rounded-pill mt-5 btn-complete" type="button">
                            Complete Order <i class="fas fa-check-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card checkout-card p-4 sticky-top" style="top: 20px;">
                    <h4 class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Order Summary</span>
                        <span class="badge badge-count rounded-pill">{{ $cartItems->count() }}</span>
                    </h4>

                    <ul class="list-group mb-3">
                        @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    {{-- ডাইনামিক ইমেজ লজিক --}}
                                    @php 
                                        $img = $item->variant->images->where('is_main', 1)->first() ?? $item->variant->images->first(); 
                                     @endphp
                <img src="{{ asset('storage/' . ($img->image ?? 'default.jpg')) }}" class="rounded" alt="product" width="50" height="50" style="object-fit: cover;">
                                </div>
                                <div class="ms-3">
                                    <h6 class="my-0">{{ $item->variant->product->name }}</h6>
                                    <small class="text-muted">
                    Size: {{ $item->variant->size->name ?? 'N/A' }} | Qty: {{ $item->quantity }}
                </small>
                                </div>
                            </div>
                            <span class="fw-semibold">৳ {{ number_format($item->variant->sale_price * $item->quantity, 0) }}</span>
                        </li>
                        @endforeach

                       
                    </ul>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">৳ {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping Fee</span>
                            <span class="text-success">৳ {{ number_format($shippingFee, 0) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total</h5>
                            <h4 class="text-danger fw-bold mb-0">৳ {{ number_format($total, 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

