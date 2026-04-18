@extends('frontend.layout.master')

@extends('frontend.layout.master')

@section('title', 'T-Shirt Collection')

@section('content')
    <section class="container my-5">

        {{-- Title --}}
        <h2 class="text-center fw-bold mb-4">
            {{ $products->first()->category->name ?? 'Collection' }}
        </h2>

        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">

                    @php
                        $availableSizes = $product->variants->map(function ($v) {
                            return ['id' => $v->id, 'size_name' => optional($v->size)->name ?? 'N/A'];
                        })->toArray();

                        $firstVariant = $product->variants->first();

                        // Image
                        $mainImage = optional($firstVariant)->images->where('is_main', 1)->first()
                            ?? optional($firstVariant)->images->first();

                        $imagePath = $mainImage
                            ? asset('storage/' . $mainImage->image)
                            : asset('assets/images/placeholder.jpg');

                        // Price & Stock
                        $salePrice = $firstVariant->sale_price ?? 0;
                        $oldPrice = $firstVariant->regular_price ?? 0;
                        $stock = $firstVariant->stock ?? 0;

                        // Discount %
                        $discount = $oldPrice > 0
                            ? round((($oldPrice - $salePrice) / $oldPrice) * 100)
                            : 0;

                        // Save Amount
                        $saveAmount = $oldPrice - $salePrice;
                    @endphp

                    <div class="card h-100 product-card border-0">

                        {{-- Image + Badge --}}
                        <div class="position-relative">

                            {{-- Discount Badge --}}
                            @if($discount > 0)
                                <span class="badge bg-danger discount-badge">
                                    -{{ $discount }}%
                                </span>
                            @endif

                            {{-- Product Image --}}
                            <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="card-img-top product-img">
                        </div>

                        {{-- Body --}}
                        <div class="card-body text-center">

                            {{-- Product Name --}}
                            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>

                            {{-- SKU --}}
                            <p class="text-muted small mb-1">
                                SKU: <span class="fw-semibold">{{ $firstVariant->sku ?? 'N/A' }}</span>
                            </p>

                            {{-- Category --}}
                            <p class="text-muted small mb-1">
                                {{ $product->category->name ?? 'Collection' }}
                            </p>

                            {{-- Stock --}}
                            <p class="small mb-2 {{ $stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $stock > 0 ? 'In Stock (' . $stock . ')' : 'Out of Stock' }}
                            </p>

                            {{-- Price --}}
                            <div class="mb-2">
                                <span class="fw-bold text-danger fs-6">
                                    ৳ {{ number_format($salePrice, 0) }}
                                </span>

                                @if($oldPrice > $salePrice)
                                    <span class="text-muted text-decoration-line-through ms-2 small">
                                        ৳ {{ number_format($oldPrice, 0) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Save Amount --}}
                            @if($saveAmount > 0)
                                <p class="text-success small mb-0">
                                    You Save ৳ {{ number_format($saveAmount, 0) }}
                                </p>
                            @endif

                        </div>

                        {{-- Footer --}}
                        <div class="card-footer bg-white border-0 pb-3">
                            <button class="btn btn-dark w-100 rounded-pill" onclick="openSizeModal(this)"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $salePrice }}"
                                data-sizes="{{ json_encode($availableSizes) }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                {{ $stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>

    </section>

    <div class="modal fade" id="sizeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Select Your Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="modalProductName" class="fw-semibold text-muted mb-3"></p>
                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-3" id="sizeOptions">
                        {{-- JS will insert sizes here --}}
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" id="confirmAddToCart" class="btn btn-dark rounded-pill px-5 py-2">Confirm Add to
                        Cart</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('styles')
    <style>
        /* Card */
        .product-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        /* Image */
        .product-img {
            height: 260px;
            object-fit: cover;
        }

        /* Discount Badge */
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px;
            padding: 5px 8px;
            border-radius: 6px;
        }

        /* Button */
        .btn-dark {
            transition: 0.3s;
        }

        .btn-dark:hover {
            opacity: 0.9;
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
            const sizeLabel = selectedOption.value;
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (isLoggedIn) {
                // Database logic
                fetch("{{ url('/cart/add-db') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ variant_id: variantId, quantity: 1 })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            updateCartBadge(data.total_count);
                            alert('Success!');
                        }
                    });
            } else {
                // LocalStorage logic
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                let existing = cart.find(i => i.variant_id == variantId);
                if (existing) { existing.quantity++; }
                else { cart.push({ variant_id: variantId, name: selectedProductData.name, price: selectedProductData.price, size: sizeLabel, image: selectedProductData.image, quantity: 1 }); }
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                if (typeof updateCartBadge === "function") updateCartBadge();
                alert('Added to guest cart!');
            }
            bootstrap.Modal.getInstance(document.getElementById('sizeModal')).hide();
        });
    </script>
@endsection