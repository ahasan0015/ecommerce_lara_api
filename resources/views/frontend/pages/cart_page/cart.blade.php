@extends('frontend.layout.master')

@section('title', 'Shopping Cart')

@section('css')
<style>
    :root {
        --primary-color: #212529;
        --accent-danger: #dc3545;
        --bg-light: #f8f9fa;
        --border-radius: 12px;
    }

    .cart-table-wrapper {
        background: #fff;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    #cart-table thead {
        background-color: var(--primary-color);
        color: #fff;
    }

    #cart-table thead th {
        border: none;
        padding: 18px;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .product-name {
        color: var(--primary-color);
        transition: color 0.3s;
        text-decoration: none;
    }

    .product-name:hover {
        color: var(--accent-danger);
    }

    .qty-input {
        max-width: 45px;
        font-weight: bold;
        border-left: none;
        border-right: none;
    }

    .summary-card {
        border-radius: var(--border-radius);
        background: var(--bg-light);
        border: 1px solid #eee;
    }

    /* Professional Mobile Card UI */
    @media (max-width: 768px) {
        #cart-table thead { display: none; }
        
        #cart-table, #cart-table tbody, #cart-table tr, #cart-table td {
            display: block;
            width: 100%;
        }

        #cart-table tr {
            margin-bottom: 1.5rem;
            border: 1px solid #eee !important;
            border-radius: var(--border-radius);
            padding: 15px;
            background: #fff;
            position: relative;
        }

        #cart-table td {
            text-align: right;
            padding: 10px 0;
            border: none;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #cart-table td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #888;
        }

        #cart-table td:first-child {
            display: block;
            text-align: center;
            background: var(--bg-light);
            margin: -15px -15px 10px -15px;
            padding: 20px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        #cart-table td:first-child::before { display: none; }
        
        #cart-table td:last-child { border: none; padding-top: 15px; }
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0 uppercase" style="letter-spacing: 1px;">Shopping Bag</h2>
        <span class="text-muted small fw-bold uppercase">Safe & Secure Checkout</span>
    </div>

    <div id="cart-container">
        <div class="table-responsive cart-table-wrapper">
            <table class="table align-middle m-0" id="cart-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Product</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th style="width: 160px;">Quantity</th>
                        <th>Subtotal</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    @if(Auth::check())
                        @forelse($cartItems as $item)
                        @php
                            $mainImage = $item->variant->images->where('is_main', 1)->first() ?? $item->variant->images->first();
                            $finalImage = $mainImage ? $mainImage->image : $item->variant->product->main_image;
                            $imagePath = $finalImage ? asset('storage/' . $finalImage) : asset('assets/images/placeholder.jpg');
                        @endphp
                        <tr data-id="{{ $item->id }}">
                            <td data-label="Image">
                                <img src="{{ $imagePath }}" class="rounded shadow-sm" style="width: 80px; height: 100px; object-fit: cover;">
                            </td>
                            <td data-label="Details">
                                <div class="text-start">
                                    <strong class="product-name d-block">{{ $item->variant->product->product_name ?? $item->variant->product->name }}</strong>
                                    <span class="badge bg-light text-dark border mt-1">Size: {{ $item->size ?? 'N/A' }}</span>
                                    <div class="small text-muted mt-1 uppercase" style="font-size: 0.7rem;">SKU: {{ $item->variant->sku }}</div>
                                </div>
                            </td>
                            <td data-label="Price" class="fw-medium">৳{{ number_format($item->variant->sale_price, 0) }}</td>
                            <td data-label="Quantity">
                                <div class="input-group input-group-sm justify-content-md-start justify-content-end">
                                    <button class="btn btn-dark" onclick="updateDbQty('{{ $item->id }}', 'decrease')">-</button>
                                    <input type="text" class="form-control text-center bg-white border-dark qty-input" value="{{ $item->quantity }}" readonly>
                                    <button class="btn btn-dark" onclick="updateDbQty('{{ $item->id }}', 'increase')">+</button>
                                </div>
                            </td>
                            <td data-label="Subtotal" class="fw-bold text-danger">৳{{ number_format($item->variant->sale_price * $item->quantity, 0) }}</td>
                            <td data-label="Action" class="text-md-end">
                                <button onclick="removeFromDbCart('{{ $item->id }}')" class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                                    <i class="far fa-trash-alt me-1"></i> <span class="d-md-none">Remove</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted fs-5">Your cart is currently empty!</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row mt-5 g-4">
            <div class="col-md-7 order-2 order-md-1">
                <a href="{{ url('/') }}" class="btn btn-outline-dark px-4 py-2 fw-bold text-uppercase" style="font-size: 0.8rem; border-radius: 50px;">
                    <i class="fas fa-chevron-left me-2"></i> Continue Shopping
                </a>
            </div>
            <div class="col-md-5 order-1 order-md-2">
                <div class="summary-card p-4 shadow-sm border">
                    <h5 class="fw-bold mb-4 border-bottom pb-2 uppercase" style="letter-spacing: 1px;">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-3 fs-5">
                        <span class="text-muted">Grand Total:</span>
                        <h3 id="total-amount" class="fw-bold text-dark m-0">
                            ৳{{ Auth::check() ? number_format($cartItems->sum(fn($i) => $i->variant->sale_price * $i->quantity), 0) : '0' }}
                        </h3>
                    </div>
                    <p class="small text-muted mb-4">* Shipping and taxes calculated at checkout.</p>
                    <a href="{{ route('checkout') }}" class="btn btn-dark w-100 py-3 fw-bold text-uppercase shadow-lg" style="border-radius: 50px; letter-spacing: 1px;">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (!isLoggedIn) {
                renderGuestCart();
            } else {
                syncGuestCartWithDatabase();
            }
        });

        /**
         *
         */
        function renderGuestCart() {
            const cartBody = document.getElementById('cart-body');
            const totalAmountDisplay = document.getElementById('total-amount');
            let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];

            if (cart.length === 0) {
                cartBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Your cart is empty!</td></tr>';
                totalAmountDisplay.innerHTML = 'Total: ৳ 0';
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach((item, index) => {
                let subtotal = item.price * item.quantity;
                total += subtotal;

                // ইমেজ ইউআরএল চেক
                let imgSrc = item.image ? (item.image.includes('http') ? item.image : `/storage/${item.image}`) : '/assets/images/placeholder.jpg';

                html += `
                    <tr>
                        <td><img src="${imgSrc}" class="img-thumbnail" style="width: 80px; height: 100px; object-fit: cover;"></td>
                        <td>
                            <strong class="text-dark">${item.name}</strong>
                            <p class="small mb-0 text-muted mt-1">
                                <span class="badge bg-light text-dark border">Size: ${item.size || 'N/A'}</span>
                            </p>
                        </td>
                        <td>৳ ${item.price.toLocaleString()}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-dark fw-bold" onclick="updateGuestQty(${index}, -1)">-</button>
                                <input type="text" class="form-control text-center bg-white border-dark fw-bold" value="${item.quantity}" readonly>
                                <button class="btn btn-outline-dark fw-bold" onclick="updateGuestQty(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">৳ ${subtotal.toLocaleString()}</td>
                        <td>
                            <button onclick="removeFromGuestCart(${index})" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </td>
                    </tr>`;
            });

            cartBody.innerHTML = html;
            totalAmountDisplay.innerHTML = `Total: ৳ ${total.toLocaleString()}`;
        }

        function updateGuestQty(index, change) {
            let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            if (cart[index]) {
                cart[index].quantity += change;
                if (cart[index].quantity < 1) cart[index].quantity = 1;
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                renderGuestCart();
                if (typeof updateCartBadge === "function") updateCartBadge();
            }
        }

        function removeFromGuestCart(index) {
            if (confirm('Remove this item?')) {
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                cart.splice(index, 1);
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                renderGuestCart();
                if (typeof updateCartBadge === "function") updateCartBadge();
            }
        }

        function updateDbQty(cartId, action) {
            fetch("{{ url('/cart/update-quantity') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    action: action
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // আপডেট সফল হলে পেজ রিলোড হবে নতুন ভ্যালু দেখানোর জন্য
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update quantity');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Something went wrong!');
                });
        }
        function removeFromDbCart(cartId) {
            if (confirm('Are you sure you want to remove this item?')) {
                // এখানে fetch এর ভেতরে headers অংশটি খেয়াল করুন
                fetch("{{ url('/cart/remove') }}/" + cartId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}", // লারাভেলের সিকিউরিটির জন্য
                        "Content-Type": "application/json",    // আমরা কি ধরনের ডেটা পাঠাচ্ছি
                        "Accept": "application/json"           // আমরা কি ধরনের রেসপন্স আশা করছি
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload(); // ডিলিট সফল হলে পেজ রিলোড হবে
                        } else {
                            alert('আইটেমটি রিমুভ করা যায়নি। আবার চেষ্টা করুন।');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('সার্ভারে সমস্যা হয়েছে।');
                    });
            }
        }
        function syncGuestCartWithDatabase() {
            let guestCart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            if (guestCart.length > 0) {
                fetch("{{ url('/cart/sync') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_data: guestCart })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'synced') {
                            localStorage.removeItem('guest_cart');
                            location.reload();
                        }
                    });
            }
        }
    </script>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (!isLoggedIn) {
                renderGuestCart();
            } else {
                syncGuestCartWithDatabase();
            }
        });

        /**
         *
         */
        function renderGuestCart() {
            const cartBody = document.getElementById('cart-body');
            const totalAmountDisplay = document.getElementById('total-amount');
            let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];

            if (cart.length === 0) {
                cartBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Your cart is empty!</td></tr>';
                totalAmountDisplay.innerHTML = 'Total: ৳ 0';
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach((item, index) => {
                let subtotal = item.price * item.quantity;
                total += subtotal;

                // ইমেজ ইউআরএল চেক
                let imgSrc = item.image ? (item.image.includes('http') ? item.image : `/storage/${item.image}`) : '/assets/images/placeholder.jpg';

                html += `
                    <tr>
                        <td><img src="${imgSrc}" class="img-thumbnail" style="width: 80px; height: 100px; object-fit: cover;"></td>
                        <td>
                            <strong class="text-dark">${item.name}</strong>
                            <p class="small mb-0 text-muted mt-1">
                                <span class="badge bg-light text-dark border">Size: ${item.size || 'N/A'}</span>
                            </p>
                        </td>
                        <td>৳ ${item.price.toLocaleString()}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-dark fw-bold" onclick="updateGuestQty(${index}, -1)">-</button>
                                <input type="text" class="form-control text-center bg-white border-dark fw-bold" value="${item.quantity}" readonly>
                                <button class="btn btn-outline-dark fw-bold" onclick="updateGuestQty(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">৳ ${subtotal.toLocaleString()}</td>
                        <td>
                            <button onclick="removeFromGuestCart(${index})" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </td>
                    </tr>`;
            });

            cartBody.innerHTML = html;
            totalAmountDisplay.innerHTML = `Total: ৳ ${total.toLocaleString()}`;
        }

        function updateGuestQty(index, change) {
            let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            if (cart[index]) {
                cart[index].quantity += change;
                if (cart[index].quantity < 1) cart[index].quantity = 1;
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                renderGuestCart();
                if (typeof updateCartBadge === "function") updateCartBadge();
            }
        }

        function removeFromGuestCart(index) {
            if (confirm('Remove this item?')) {
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                cart.splice(index, 1);
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                renderGuestCart();
                if (typeof updateCartBadge === "function") updateCartBadge();
            }
        }

        function updateDbQty(cartId, action) {
            fetch("{{ url('/cart/update-quantity') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    action: action
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update quantity');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Something went wrong!');
                });
        }
        function removeFromDbCart(cartId) {
            if (confirm('Are you sure you want to remove this item?')) {
                // এখানে fetch এর ভেতরে headers অংশটি খেয়াল করুন
                fetch("{{ url('/cart/remove') }}/" + cartId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}", 
                        "Content-Type": "application/json",    
                        "Accept": "application/json"           
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload(); 
                        } else {
                            alert('আইটেমটি রিমুভ করা যায়নি। আবার চেষ্টা করুন।');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('সার্ভারে সমস্যা হয়েছে।');
                    });
            }
        }
        function syncGuestCartWithDatabase() {
            let guestCart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            if (guestCart.length > 0) {
                fetch("{{ url('/cart/sync') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_data: guestCart })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'synced') {
                            localStorage.removeItem('guest_cart');
                            location.reload();
                        }
                    });
            }
        }
    </script>
@endsection

