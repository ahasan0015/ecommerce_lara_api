@extends('frontend.layout.master')

@section('title', 'Shirt Collection')

@section('content')
    <section class="container my-5">
        <h2 class="text-center fw-bold mb-4">
            Shirt Collection
        </h2>

        @php
            // রিয়েক্টের JSON ডাটার মতো এখানে একটি PHP অ্যারে তৈরি করা হয়েছে
            $tshirts = [
                ['id' => 1, 'name' => 'Classic Black T-Shirt', 'price' => 999, 'image' => 't-1.jpg', 'category' => 'Men'],
                ['id' => 2, 'name' => 'White Cotton T-Shirt', 'price' => 899, 'image' => 't-6.jpg', 'category' => 'Men'],
                ['id' => 3, 'name' => 'Graphic Printed T-Shirt', 'price' => 1199, 'image' => 't-5.jpg', 'category' => 'Women'],
                ['id' => 4, 'name' => 'Oversized T-Shirt', 'price' => 1299, 'image' => 't-3.jpg', 'category' => 'Unisex'],
                ['id' => 5, 'name' => 'Oversized T-Shirt', 'price' => 1299, 'image' => 't-3.jpg', 'category' => 'Unisex'],
                ['id' => 6, 'name' => 'Oversized T-Shirt', 'price' => 1299, 'image' => 't-3.jpg', 'category' => 'Unisex'],
                ['id' => 7, 'name' => 'Oversized T-Shirt', 'price' => 1299, 'image' => 't-3.jpg', 'category' => 'Unisex'],
                ['id' => 8, 'name' => 'Oversized T-Shirt', 'price' => 1299, 'image' => 't-3.jpg', 'category' => 'Unisex'],
            ];
        @endphp

        <div class="row g-4">
            @foreach($tshirts as $item)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="{{ asset('assets/images/products/tshirt/' . $item['image']) }}" alt="{{ $item['name'] }}"
                            class="card-img-top" style="height: 280px; object-fit: cover;">

                        <div class="card-body text-center">
                            <h5 class="card-title h6 fw-bold">{{ $item['name'] }}</h5>
                            <p class="text-muted small mb-2">{{ $item['category'] }}</p>
                            <h6 class="fw-bold text-danger">
                                ৳ {{ number_format($item['price'], 0) }}
                            </h6>
                        </div>

                        <div class="card-footer bg-white border-0 pb-3">
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-dark w-100 rounded-pill">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
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

            // এখানে ফিক্স করা হয়েছে
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

            // চেক করুন এই ফাংশনটি আপনার মাস্টার ফাইলে আছে কিনা
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
                    if (data.status === 'success') {
                        // সফল হলে পেজ রিলোড বা ব্যাজ আপডেট
                        if (typeof updateCartBadge === "function") updateCartBadge();
                        alert('Product added to cart successfully!');
                    } else {
                        alert(data.message || 'Failed to add to cart.');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('An error occurred. Please check console.');
                });
        }
    </script>

@endsection

@section('styles')
    <style>
        .product-card {
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }
    </style>
@endsection