@extends('frontend.layout.master')

@section('title', 'Panjabi Collection')

@section('content')
    <section class="container my-4 my-md-5">
        <h2 class="text-center fw-bold mb-3 mb-md-4 fs-3 fs-md-2">
            {{ $products->first()->category->name ?? 'Panjabi Collection' }}
        </h2>

        <div class="row g-3 g-md-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card overflow-hidden">

                        @php
                            $imagePath = $product->main_image
                                ? asset('storage/' . $product->main_image)
                                : asset('assets/images/placeholder.jpg');

                            $firstVariant = $product->variants->first();

                            $availableSizes = $product->variants->map(function ($v) {
                                return [
                                    'id' => $v->id,
                                    'size_name' => optional($v->size)->name ?? 'N/A'
                                ];
                            })->toArray();
                        @endphp

                        <a href="{{ route('product.details', $product->id) }}" class="d-block bg-light">
                            <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="card-img-top product-img"
                                style="width: 100%; object-fit: contain; background-color: #f8f9fa;">
                        </a>

                        <div class="card-body text-center p-2 p-md-3">
                            <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title h6 fw-bold mb-1 text-truncate">{{ $product->name }}</h5>
                            </a>
                            <p class="text-muted mb-2 d-none d-sm-block" style="font-size: 0.75rem;">Exclusive Collection</p>
                            <h6 class="fw-bold text-danger mb-0">
                                ৳ {{ number_format($firstVariant->sale_price ?? 0, 0) }}
                            </h6>
                        </div>

                        <div class="card-footer bg-white border-0 pb-3 pt-0 px-2 px-md-3">
                            <button class="btn btn-dark w-100 rounded-pill py-2 btn-sm-mobile" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" data-price="{{ $firstVariant->sale_price ?? 0 }}"
                                data-sizes="{{ json_encode($availableSizes) }}" onclick="openSizeModal(this)">
                                <small>Add to Cart</small>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p>No products found in this collection.</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-sm-custom">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold w-100 text-center" id="sizeModalLabel">Select Your Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="modalProductName" class="fw-semibold text-muted mb-3 small"></p>
                    <div class="d-flex justify-content-center flex-wrap gap-3 mb-3" id="sizeOptions"></div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" id="confirmAddToCart" class="btn btn-dark rounded-pill px-5 py-2">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        /* Responsive image heights */
        .product-img {
            height: 200px;
            /* Default for mobile */
        }

        @media (min-width: 768px) {
            .product-img {
                height: 300px;
                /* Tablet and Up */
            }
        }

        /* Better touch targets for size buttons */
        .btn-outline-dark {
            min-width: 50px;
        }

        /* Prevent long names from breaking card heights */
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Small adjustments for the 'Add to Cart' button text on very small screens */
        @media (max-width: 375px) {
            .btn-sm-mobile {
                font-size: 10px;
                padding: 8px 4px !important;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        let selectedProductData = null;

        function openSizeModal(button) {
            const productId = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const sizesData = button.getAttribute('data-sizes');
            const productImage = button.closest('.product-card').querySelector('img').src;

            let sizes = [];
            try {
                sizes = JSON.parse(sizesData);
            } catch (e) {
                console.error("JSON parse error:", e);
            }

            selectedProductData = {
                id: productId,
                name: name,
                price: price,
                image: productImage
            };

            document.getElementById('modalProductName').innerText = name;

            let sizeHtml = '';
            if (Array.isArray(sizes) && sizes.length > 0) {
                sizes.forEach(item => {
                    sizeHtml += `
                                        <div class="m-1">
                                            <input type="radio" class="btn-check" name="productSize" 
                                                   id="v_${item.id}" value="${item.size_name}" data-variant-id="${item.id}" autocomplete="off">
                                            <label class="btn btn-outline-dark px-3 py-2" for="v_${item.id}">${item.size_name}</label>
                                        </div>
                                    `;
                });
            } else {
                sizeHtml = '<p class="text-danger">Out of stock!</p>';
            }

            document.getElementById('sizeOptions').innerHTML = sizeHtml;

            const modalEl = document.getElementById('sizeModal');
            const myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }

        document.getElementById('confirmAddToCart').addEventListener('click', function () {
            const selectedOption = document.querySelector('input[name="productSize"]:checked');

            if (!selectedOption) {
                alert('Please select a size!');
                return;
            }

            const variantId = selectedOption.getAttribute('data-variant-id');
            const sizeLabel = selectedOption.value;


            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (isLoggedIn) {
                addToDatabaseCart(variantId, sizeLabel);
            } else {
                addToLocalStorageCart(variantId, selectedProductData.name, selectedProductData.price, sizeLabel, selectedProductData.image);
            }

            const modalEl = document.getElementById('sizeModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        });

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


            if (typeof updateCartBadge === "function") {
                updateCartBadge();
            }

            alert(`Success! ${name} (${size}) added to cart.`);
        }

        function addToDatabaseCart(variantId, size) {
            fetch("{{ url('/cart/add-db') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    quantity: 1,
                    size: size
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response from server:", data);
                    if (data.status === 'success') {

                        updateCartBadge(data.total_count);

                        alert('Product added to cart successfully!');


                        const modalEl = document.getElementById('sizeModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
@endsection