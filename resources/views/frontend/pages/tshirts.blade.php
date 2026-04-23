@extends('frontend.layout.master')

@section('title', 'Mens T-Shirt Collection')

@section('content')
    <section class="container my-4 my-md-5">
        {{-- Title --}}
        <h2 class="text-center fw-bold mb-3 mb-md-4 fs-3 fs-md-2">
            {{ $products->first()->category->name ?? 'Mens T-Shirt Collection' }}
        </h2>

        {{-- g-3 for mobile, g-4 for desktop --}}
        <div class="row g-3 g-md-4">
            @foreach($products as $product)
                {{-- 2 columns on mobile (col-6), 4 on desktop (col-lg-3) --}}
                <div class="col-6 col-md-4 col-lg-3">
                    @php
                        $firstVariant = $product->variants->first();
                        $imagePath = $product->main_image
                            ? asset('storage/' . $product->main_image)
                            : asset('assets/images/placeholder.jpg');

                        $availableSizes = $product->variants->map(function ($v) {
                            return [
                                'id' => $v->id,
                                'size_name' => optional($v->size)->name ?? 'N/A'
                            ];
                        })->toArray();

                        $salePrice = $firstVariant->sale_price ?? 0;
                        $oldPrice = $product->base_price ?? 0;
                        $stock = $firstVariant->stock ?? 0;

                        $discount = ($oldPrice > $salePrice)
                            ? round((($oldPrice - $salePrice) / $oldPrice) * 100)
                            : 0;
                        $saveAmount = $oldPrice - $salePrice;
                    @endphp

                    <div class="card h-100 product-card border-0 shadow-sm overflow-hidden">
                        {{-- Image Section --}}
                        <div class="position-relative bg-light">
                            @if($discount > 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2 shadow-sm" style="z-index: 2;">
                                    -{{ $discount }}%
                                </span>
                            @endif

                            <a href="{{ route('product.details', $product->id) }}">
                                <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="card-img-top product-img">
                            </a>
                        </div>

                        {{-- Body Section --}}
                        <div class="card-body text-center p-2 p-md-3">
                            <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>

                            <p class="text-muted small mb-1 d-none d-sm-block">
                                SKU: <span class="fw-semibold">{{ $firstVariant->sku ?? 'N/A' }}</span>
                            </p>

                            <p class="mb-2 {{ $stock > 0 ? 'text-success' : 'text-danger' }}" style="font-size: 0.75rem;">
                                <strong>{{ $stock > 0 ? 'In Stock' : 'Out of Stock' }}</strong>
                                <span class="d-none d-md-inline">({{ $stock }})</span>
                            </p>

                            {{-- Price --}}
                            <div class="mb-1">
                                <span class="fw-bold text-danger fs-6">৳{{ number_format($salePrice, 0) }}</span>
                                @if($oldPrice > $salePrice)
                                    <br class="d-block d-md-none"> {{-- Stack prices on very small screens --}}
                                    <span class="text-muted text-decoration-line-through ms-md-2 small" style="font-size: 0.8rem;">
                                        ৳{{ number_format($oldPrice, 0) }}
                                    </span>
                                @endif
                            </div>

                            @if($saveAmount > 0)
                                <p class="text-success fw-bold d-none d-md-block" style="font-size: 0.7rem;">Save ৳{{ number_format($saveAmount, 0) }}</p>
                            @endif
                        </div>

                        {{-- Footer/Button --}}
                        <div class="card-footer bg-white border-0 pb-3 pt-0 px-2 px-md-3">
                            <button class="btn btn-dark w-100 rounded-pill py-2 btn-cart-responsive" onclick="openSizeModal(this)"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $salePrice }}"
                                data-sizes="{{ json_encode($availableSizes) }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                <small class="fw-bold">{{ $stock > 0 ? 'Add to Cart' : 'Stock Out' }}</small>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Size Selection Modal --}}
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold w-100 text-center">Select Your Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="modalProductName" class="fw-semibold text-muted mb-3 small"></p>
                    <div class="d-flex justify-content-center flex-wrap gap-3 mb-3" id="sizeOptions"></div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" id="confirmAddToCart" class="btn btn-dark rounded-pill px-5 py-2">
                        Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .product-card {
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        /* Responsive image heights */
        .product-img {
            height: 180px; /* Mobile */
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        @media (min-width: 768px) {
            .product-img {
                height: 280px; /* Desktop */
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            }
        }

        /* Better touch targets for mobile sizes */
        .btn-outline-dark {
            min-width: 55px;
            border-radius: 8px;
        }

        .btn-check:checked+.btn-outline-dark {
            background-color: #000;
            color: #fff;
        }

        /* UI Adjustments for small phones */
        @media (max-width: 375px) {
            .btn-cart-responsive small {
                font-size: 11px;
            }
            .product-img {
                height: 160px;
            }
        }
    </style>
@endsection


@section('js')
    <script>
        let selectedProductData = null;

        function openSizeModal(button) {
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const sizesData = JSON.parse(button.getAttribute('data-sizes'));
            const productImage = button.closest('.product-card').querySelector('img').src;

            selectedProductData = { name, price, image: productImage };
            document.getElementById('modalProductName').innerText = name;

            let sizeHtml = '';
            sizesData.forEach(item => {
                sizeHtml += `
                <div class="m-1">
                    <input type="radio" class="btn-check" name="productSize" id="v_${item.id}" data-variant-id="${item.id}" value="${item.size_name}">
                    <label class="btn btn-outline-dark px-3 py-2" for="v_${item.id}">${item.size_name}</label>
                </div>`;
            });

            document.getElementById('sizeOptions').innerHTML = sizeHtml;
            new bootstrap.Modal(document.getElementById('sizeModal')).show();
        }

        document.getElementById('confirmAddToCart').addEventListener('click', function () {
            const selectedOption = document.querySelector('input[name="productSize"]:checked');
            if (!selectedOption) { alert('Please select a size!'); return; }

            const variantId = selectedOption.getAttribute('data-variant-id');
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (isLoggedIn) {
                fetch("{{ url('/cart/add-db') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ variant_id: variantId, quantity: 1 })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (typeof updateCartBadge === 'function') updateCartBadge(data.total_count);
                            alert('Product added to cart!');
                        }
                    });
            } else {
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                let existing = cart.find(i => i.variant_id == variantId);
                if (existing) { existing.quantity++; }
                else {
                    cart.push({
                        variant_id: variantId,
                        name: selectedProductData.name,
                        price: selectedProductData.price,
                        image: selectedProductData.image,
                        quantity: 1
                    });
                }
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                if (typeof updateCartBadge === "function") updateCartBadge();
                alert('Added to guest cart!');
            }
            bootstrap.Modal.getInstance(document.getElementById('sizeModal')).hide();
        });
    </script>
@endsection