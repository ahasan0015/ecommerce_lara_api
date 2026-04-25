@extends('frontend.layout.master')

@section('title', 'Checkout')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/check_out.css') }}">
@endsection

@section('content')
    <div class="container my-5">
        {{-- Alert Messages --}}
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="row g-5">
            {{-- Shipping Address Form --}}
            <div class="col-md-7 col-lg-8">
                <div class="card checkout-card p-4 shadow-sm border-0">
                    <h4 class="mb-4 fw-bold"><i class="fas fa-truck me-2"></i>Shipping Address</h4>

                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Enter your full name"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">+88</span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}" placeholder="017XXXXXXXX" required>
                                </div>
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Email <span
                                        class="text-muted small">(Optional)</span></label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ auth()->user()->email ?? '' }}" placeholder="you@example.com">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Full Address</label>
                                <textarea class="form-control" name="address" rows="3"
                                    placeholder="House no, Road no, Area..." {{ old('address') }} required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City</label>
                                <select class="form-select" name="city" id="city_select" required>
                                    <option value="">Choose...</option>
                                    <option value="Dhaka">Dhaka (Inside)</option>
                                    <option value="Chittagong">Chittagong (Outside)</option>
                                    <option value="Sylhet">Sylhet (Outside)</option>
                                    <option value="Rajshahi">Rajshahi (Outside)</option>
                                    <option value="Rangpur">Rangpur (Outside)</option>
                                    <option value="Khulna">Khulna (Outside)</option>
                                    <option value="Barisal">Barisal (Outside)</option>
                                    <option value="Mymensingh">Mymensingh (Outside)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thana / Area</label>
                                <input type="text" name="thana" class="form-control" value="{{ old('thana') }}"
                                    placeholder="Enter your area">
                            </div>
                        </div>

                        <hr class="my-5">

                        <h4 class="mb-4 fw-bold"><i class="fas fa-credit-card me-2"></i>Payment Method</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check border p-3 rounded mb-3 payment-option">
                                    <input id="cod" name="payment_method" type="radio" value="COD" checked
                                        class="form-check-input">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="cod">
                                        <span>Cash on Delivery</span>
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                    </label>
                                </div>

                                <div class="form-check border p-3 rounded payment-option opacity-50">
                                    <input id="online" name="payment_method" type="radio" class="form-check-input" disabled>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="online">
                                        <span>Online Payment (Coming Soon)</span>
                                        <span class="badge bg-secondary">Soon</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" required id="terms">
                            <label class="form-check-label small" for="terms">
                                I agree to Terms & Conditions
                            </label>
                        </div>

                        <button class="w-100 btn btn-primary btn-lg rounded-pill mt-5" type="submit">
                            Complete Order <i class="fas fa-check-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-md-5 col-lg-4">
                <div class="card checkout-card p-4 sticky-top" style="top: 20px;">
                    <h4 class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Order Summary</span>
                        <span class="badge bg-primary rounded-pill">{{ $cartItems->count() }}</span>
                    </h4>

                    <ul class="list-group mb-3 shadow-sm">
                        @foreach($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @php
                                            $variant = $item->variant;
                                            $img = $variant->images->where('is_main', 1)->first() ?? $variant->images->first();
                                            if ($img && $img->image) {
                                                $url = asset('storage/' . $img->image);
                                            } elseif ($variant->product && $variant->product->main_image) {
                                                $url = asset('storage/' . $variant->product->main_image);
                                            } else {
                                                $url = asset('assets/images/placeholder.jpg');
                                            }
                                        @endphp

                                        <img src="{{ $url }}" class="rounded shadow-sm" width="50" height="50"
                                            style="object-fit: cover;" alt="product">
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="my-0 small fw-bold">{{ Str::limit($item->variant->product->name, 20) }}</h6>
                                        <small class="text-muted d-block">Size:
                                            {{ $item->variant->size->name ?? 'N/A' }}
                                        </small>
                                        <small
                                            class="d-block {{ $item->variant->stock > 0 ? 'text-success' : 'text-danger' }} fw-bold"
                                            style="font-size: 0.75rem;">
                                            Availability: {{ $item->variant->stock }} left in stock
                                        </small>
                                        <div class="d-flex align-items-center mt-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 update-qty"
                                                data-id="{{ $item->id }}" data-action="decrease">-</button>
                                            <span class="mx-2 fw-bold text-primary">{{ $item->quantity }}</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 update-qty"
                                                data-id="{{ $item->id }}" data-action="increase" {{ ($item->variant && $item->quantity >= $item->variant->stock) ? 'disabled' : '' }}>
                                                +
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <span
                                    class="fw-semibold">৳{{ number_format($item->variant->sale_price * $item->quantity, 0) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">৳<span id="subtotal_val">{{ number_format($subtotal, 0) }}</span></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping Fee</span>
                            <span class="text-success fw-bold">৳<span id="shipping_fee_display">0</span></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total</h5>
                            <h4 class="text-danger fw-bold mb-0">৳<span
                                    id="total_val">{{ number_format($subtotal, 0) }}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.update-qty', function () {
            let cartItemId = $(this).data('id');
            let actionType = $(this).data('action');

            $.ajax({
                url: "{{ route('cart.update.quantity') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cartItemId,
                    action: actionType
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || "Something went wrong!");
                }
            });
        });

        $(document).ready(function () {
            function updatePricing() {
                let city = $('#city_select').val();
                let shipping = 0;


                let subtotal = parseInt($('#subtotal_val').text().replace(/,/g, ''));

                if (city === 'Dhaka') {
                    shipping = 60;
                } else if (city !== '') {
                    shipping = 150;
                }

                $('#shipping_fee_display').text(shipping);
                let total = subtotal + shipping;
                $('#total_val').text(total.toLocaleString());
            }

            $('#city_select').on('change', function () {
                updatePricing();
            });
        });
    </script>
@endsection