@extends('frontend.layout.master')

@section('title', 'Checkout')

@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25 red rgba(0, 0, 0, 0.1);
            border-color: #212529;
        }

        .list-group-item {
            border: none;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .payment-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .payment-option:hover {
            background-color: #f1f1f1;
        }

        .btn-complete {
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-complete:hover {
            transform: translateY(-2px);
        }

        .badge-count {
            background-color: #212529;
        }
    </style>

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

                        <button class="w-100 btn btn-dark btn-lg rounded-pill mt-5 btn-complete" type="button">
                            Complete Order <i class="fas fa-check-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card checkout-card p-4 sticky-top" style="top: 20px;">
                    <h4 class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Order Summary</span>
                        <span class="badge badge-count rounded-pill">2</span>
                    </h4>

                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="https://via.placeholder.com/50" class="rounded" alt="product">
                                </div>
                                <div class="ms-3">
                                    <h6 class="my-0">Premium Black T-Shirt</h6>
                                    <small class="text-muted">Size: L | Qty: 1</small>
                                </div>
                            </div>
                            <span class="fw-semibold">৳ 450</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="https://via.placeholder.com/50" class="rounded" alt="product">
                                </div>
                                <div class="ms-3">
                                    <h6 class="my-0">Cotton Chino Pants</h6>
                                    <small class="text-muted">Size: 32 | Qty: 1</small>
                                </div>
                            </div>
                            <span class="fw-semibold">৳ 850</span>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">৳ 1300</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping Fee</span>
                            <span class="text-success">৳ 60</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total</h5>
                            <h4 class="text-danger fw-bold mb-0">৳ 1360</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

