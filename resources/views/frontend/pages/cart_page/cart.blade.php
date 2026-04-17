@extends('frontend.layout.master')

@section('title', 'Shopping Cart')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold text-center text-uppercase">Shopping Cart</h2>
    
    <div id="cart-container">
        <div class="table-responsive">
            <table class="table align-middle shadow-sm border" id="cart-table">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 120px;">Product Image</th>
                        <th>Details</th>
                        <th>Price</th>
                        <th style="width: 150px;">Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    @if(Auth::check())
                        @forelse($cartItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>
                                @php
                                    // ভেরিয়েন্টের মেইন ইমেজ খুঁজে বের করা
                                    $mainImage = optional($item->variant->images)->where('is_main', 1)->first() 
                                                 ?? optional($item->variant->images)->first();
                                    $imagePath = $mainImage ? asset('storage/' . $mainImage->image) : asset('assets/images/placeholder.jpg');
                                @endphp
                                <img src="{{ $imagePath }}" alt="Product" class="img-thumbnail shadow-sm" style="width: 80px; height: 100px; object-fit: cover;">
                            </td>
                            <td>
                                <strong class="text-dark">{{ $item->variant->product->product_name ?? $item->variant->product->name }}</strong>
                                <p class="small mb-0 text-muted mt-1">
                                    <span class="badge bg-light text-dark border">Size: {{ $item->size ?? optional($item->variant->size)->name }}</span><br>
                                    <small class="text-uppercase">SKU: {{ $item->variant->sku }}</small>
                                </p>
                            </td>
                            <td>৳ {{ number_format($item->variant->sale_price, 0) }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-dark fw-bold" onclick="updateDbQty('{{ $item->id }}', 'decrease')">-</button>
                                    <input type="text" class="form-control text-center bg-white border-dark fw-bold" value="{{ $item->quantity }}" readonly>
                                    <button class="btn btn-outline-dark fw-bold" onclick="updateDbQty('{{ $item->id }}', 'increase')">+</button>
                                </div>
                            </td>
                            <td class="fw-bold">৳ {{ number_format($item->variant->sale_price * $item->quantity, 0) }}</td>
                            <td>
                                <button onclick="removeFromDbCart('{{ $item->id }}')" class="btn btn-sm btn-outline-danger px-3">
                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Your cart is currently empty!</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <a href="{{ url('/') }}" class="btn btn-outline-dark px-4 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                </a>
            </div>
            <div class="col-md-6 text-end">
                <div class="bg-light p-4 rounded shadow-sm">
                    <h4 class="mb-3">Cart Summary</h4>
                    <h3 id="total-amount" class="fw-bold text-danger">
                        Total: ৳ {{ Auth::check() ? number_format($cartItems->sum(fn($i) => $i->variant->sale_price * $i->quantity), 0) : '0' }}
                    </h3>
                    <hr>
                    <a href="{{ url('/checkout') }}" class="btn btn-dark w-100 py-2 fs-5 rounded-pill shadow">
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
document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    
    if (!isLoggedIn) {
        renderGuestCart();
    } else {
        syncGuestCartWithDatabase();
    }
});

/**
 * গেস্ট ইউজারদের জন্য কার্ট রেন্ডার (ইমেজ হ্যান্ডলিং সহ)
 */
function renderGuestCart() {
    const cartBody = document.getElementById('cart-body');
    const totalAmountDisplay = document.getElementById('total-amount');
    let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
    
    if (cart.length === 0) {
        cartBody.innerHTML = '<tr><td colspan="6" class="text-center py-5">Your cart is empty!</td></tr>';
        totalAmountDisplay.innerText = "Total: ৳ 0";
        return;
    }

    let html = '';
    let total = 0;

    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;
        
        // গেস্ট আইটেমের ক্ষেত্রে যদি ইমেজ না থাকে তবে প্লেসহোল্ডার
        let imgSrc = item.image ? item.image : '{{ asset("assets/images/placeholder.jpg") }}';

        html += `
            <tr>
                <td>
                    <img src="${imgSrc}" alt="Product" class="img-thumbnail shadow-sm" style="width: 80px; height: 100px; object-fit: cover;">
                </td>
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
                    <button onclick="removeFromGuestCart(${index})" class="btn btn-sm btn-outline-danger px-3">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                </td>
            </tr>
        `;
    });

    cartBody.innerHTML = html;
    totalAmountDisplay.innerText = `Total: ৳ ${total.toLocaleString()}`;
}

// --- হেল্পার ফাংশনসমূহ (আগের মতোই থাকবে) ---

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
    if(confirm('Remove this item?')) {
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
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ cart_id: cartId, action: action })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') location.reload();
    });
}

function removeFromDbCart(cartId) {
    if(confirm('Are you sure you want to remove this item?')) {
        fetch("{{ url('/cart/remove') }}/" + cartId, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') location.reload();
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
            if(data.status === 'synced') {
                localStorage.removeItem('guest_cart');
                location.reload();
            }
        });
    }
}
</script>
@endsection