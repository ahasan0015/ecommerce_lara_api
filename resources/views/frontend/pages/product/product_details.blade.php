@extends('frontend.layout.master')

@section('title', $product->product_name)

@section('content')
<div class="container my-5">
    <div class="row g-4">

        <div class="col-lg-6 col-md-12">
            <div id="productCarousel" class="carousel slide shadow-sm border-0 rounded-4 overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner bg-light">
                    @php $first = true; @endphp
                    @foreach($product->variants as $variant)
                    @foreach($variant->images as $image)
                    <div class="carousel-item {{ $first ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->image) }}"
                            class="d-block w-100 product-main-img"
                            alt="{{ $product->product_name }}">
                    </div>
                    @php $first = false; @endphp
                    @endforeach
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="width: 10px; height: 10px;"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="width: 10px; height: 10px;"></span>
                </button>
            </div>

            <div class="d-flex mt-3 gap-2 overflow-auto pb-2 custom-scrollbar">
                @php $imgIndex = 0; @endphp
                @foreach($product->variants as $variant)
                @foreach($variant->images as $image)
                <img src="{{ asset('storage/' . $image->image) }}"
                    class="img-thumbnail rounded-3 thumb-img"
                    style="width: 80px; aspect-ratio: 1/1; cursor: pointer; object-fit: cover;"
                    onclick="new bootstrap.Carousel('#productCarousel').to({{ $imgIndex++ }})">
                @endforeach
                @endforeach
            </div>
        </div>

        <div class="col-lg-6 col-md-12 ps-lg-5">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active fw-semibold text-dark">{{ $product->category->name ?? 'Collection' }}</li>
                </ol>
            </nav>

            {{-- প্রোডাক্টের নাম এখানে প্রিন্ট করা হয়েছে --}}
            <p class="text-uppercase text-muted small mb-1 tracking-wider" style="letter-spacing: 1px;">
                {{ $product->category->name ?? 'Premium Collection' }}
            </p>

            <h1 class="fw-bold text-dark mb-1" style="font-size: 1.75rem; line-height: 1.2;">
                {{ $product->product_name ?? $product->name }}
            </h1>

            <div class="d-flex align-items-center mb-4">
                <span class="text-muted me-2">Color:</span>
                <span class="fw-semibold text-dark">{{ $product->variants->first()->color->name ?? 'No Color' }}</span>

                {{-- কালার যদি ছোট একটি সার্কেল আকারে দেখাতে চান --}}
                @if(isset($product->variants->first()->color->code))
                <span class="ms-2 d-inline-block rounded-circle border shadow-sm"
                    style="width: 18px; height: 18px; background-color: {{ $product->variants->first()->color->code }};">
                </span>
                @endif
            </div>
            </h2>

            <p class="text-muted small mb-4">SKU: <span class="text-dark fw-medium">{{ $product->variants->first()->sku ?? 'N/A' }}</span></p>

            <div class="d-flex align-items-center mb-3">
                <h2 class="text-danger fw-bold mb-0 me-3">
                    ৳ {{ number_format($product->variants->first()->sale_price ?? 0, 0) }}
                </h2>
            </div>

            <div id="stockStatus" class="mb-4">
                @php $totalStock = $product->variants->sum('stock'); @endphp
                @if($totalStock > 0)
                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                    <i class="fas fa-check-circle me-1"></i> In Stock (Total: {{ $totalStock }})
                </span>
                @else
                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                    <i class="fas fa-times-circle me-1"></i> Out of Stock
                </span>
                @endif
            </div>

            <div class="mb-5">
                <h6 class="fw-bold text-uppercase small mb-3">Select Your Size:</h6>
                <div class="d-flex flex-wrap gap-3" id="sizeOptions">
                    @foreach($product->variants as $variant)
                    <div class="size-wrapper">
                        <input type="radio" class="btn-check" name="productSize"
                            id="v_{{ $variant->id }}"
                            value="{{ optional($variant->size)->name }}"
                            data-variant-id="{{ $variant->id }}"
                            data-price="{{ $variant->sale_price }}"
                            data-stock="{{ $variant->stock }}"
                            {{ $variant->stock <= 0 ? 'disabled' : '' }}
                            autocomplete="off">
                        <label class="btn btn-outline-dark px-4 py-2 fw-medium rounded-3 {{ $variant->stock <= 0 ? 'opacity-50' : '' }}"
                            for="v_{{ $variant->id }}">
                            {{ optional($variant->size)->name ?? 'Free Size' }}
                            @if($variant->stock <= 0)
                                <small class="d-block text-danger" style="font-size: 10px;">Sold Out</small>
                                @endif
                        </label>
                    </div>
                    @endforeach
                </div>
                <div id="variantStockAlert" class="mt-2 small fw-bold"></div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <button id="addBtn" onclick="handleAddToCart(false)"
                        class="btn btn-dark btn-lg w-100 py-3 fw-bold rounded-pill"
                        {{ $totalStock <= 0 ? 'disabled' : '' }}>
                        ADD TO CART
                    </button>
                </div>
                <div class="col-md-6">
                    <button id="buyBtn" onclick="handleAddToCart(true)"
                        class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold rounded-pill"
                        {{ $totalStock <= 0 ? 'disabled' : '' }}>
                        BUY IT NOW
                    </button>
                </div>
            </div>

            <div class="mt-5 pt-4 border-top">
                <h5 class="fw-bold mb-3 text-uppercase small">Product Details</h5>
                <div class="text-muted fs-6" style="line-height: 1.8;">
                    {!! nl2br(e($product->description)) ?? 'No description available for this product.' !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // সাইজ সিলেকশন ও স্টক এলার্ট
    document.querySelectorAll('input[name="productSize"]').forEach(input => {
        input.addEventListener('change', function() {
            const stock = parseInt(this.getAttribute('data-stock'));
            const alertBox = document.getElementById('variantStockAlert');

            if (stock > 0 && stock <= 5) {
                alertBox.innerHTML = `<i class="fas fa-fire me-1"></i> Only ${stock} items left!`;
                alertBox.className = "mt-2 text-warning small fw-bold";
            } else if (stock > 5) {
                alertBox.innerHTML = `<i class="fas fa-check me-1"></i> In Stock`;
                alertBox.className = "mt-2 text-success small fw-bold";
            } else {
                alertBox.innerHTML = "";
            }
        });
    });

    // মেইন অ্যাড টু কার্ট হ্যান্ডলার
    function handleAddToCart(isBuyNow) {
        const selectedOption = document.querySelector('input[name="productSize"]:checked');

        if (!selectedOption) {
            alert('Please select a size first!');
            return;
        }

        const variantId = selectedOption.getAttribute('data-variant-id');
        const sizeLabel = selectedOption.value;
        const price = selectedOption.getAttribute('data-price');
        const name = "{{ $product->product_name }}";
        const image = "{{ asset('storage/' . ($product->variants->first()->images->first()->image ?? '')) }}";

        const isLoggedIn = {
            {
                Auth::check() ? 'true' : 'false'
            }
        };

        if (isLoggedIn) {
            addToDatabaseCart(variantId, sizeLabel, isBuyNow);
        } else {
            addToLocalStorageCart(variantId, name, price, sizeLabel, image);
            if (isBuyNow) window.location.href = "{{ route('cart.index') }}";
        }
    }

    // Local Storage Cart (For Guests)
    function addToLocalStorageCart(variantId, name, price, size, image) {
        let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
        let existingItem = cart.find(item => item.variant_id == variantId);

        if (existingItem) {
            existingItem.quantity = parseInt(existingItem.quantity) + 1;
        } else {
            cart.push({
                variant_id: variantId,
                name: name,
                price: parseFloat(price),
                quantity: 1,
                size: size,
                image: image
            });
        }

        localStorage.setItem('guest_cart', JSON.stringify(cart));

        // কার্ট কাউন্ট আপডেট করার জন্য (যদি আপনার মাস্টার ফাইলে এই ফাংশন থাকে)
        if (typeof updateCartBadge === "function") updateCartBadge();

        alert('Product added to cart successfully!');
    }

    // Database Cart (For Logged in Users)
    function addToDatabaseCart(variantId, size, isBuyNow) {
        fetch("{{ url('/cart/add-db') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    quantity: 1,
                    size: size
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (isBuyNow) {
                        window.location.href = "{{ route('cart.index') }}";
                    } else {
                        alert('Product added to cart!');
                        location.reload();
                    }
                } else {
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(err => console.error("Error adding to DB cart:", err));
    }
</script>
@endsection

@section('styles')
<style>
    .product-main-img {
        aspect-ratio: 2 / 3;
        width: 100%;
        object-fit: cover;
        background-color: #fdfdfd;
    }

    .btn-check:disabled+.btn-outline-dark {
        border-style: dashed;
        color: #ccc;
        pointer-events: none;
    }

    .size-wrapper label {
        min-width: 80px;
        text-align: center;
        cursor: pointer;
    }

    .thumb-img:hover {
        border-color: #212529;
    }
</style>
@endsection