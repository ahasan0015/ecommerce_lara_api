@extends('frontend.layout.master')

@section('title', $product->product_name ?? $product->name)

@section('content')
    <div class="container my-5">
        <div class="row g-4">
            {{-- image Section --}}
            <div class="col-lg-6 col-md-12">
                <div id="productCarousel" class="carousel slide shadow-sm border-0 rounded-4 overflow-hidden"
                    data-bs-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @php $first = true; @endphp

                        {{--Main Image from Products Table--}}
                        @if($product->main_image)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $product->main_image) }}" class="d-block w-100 product-main-img"
                                    alt="{{ $product->name }}">
                            </div>
                            @php $first = false; @endphp
                        @endif

                        {{-- product_images) --}}

                        @foreach($product->images as $img)
                            <div class="carousel-item {{ ($first && $loop->first) ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $img->image) }}" class="d-block w-100 product-main-img"
                                    alt="Gallery Image">
                            </div>
                        @endforeach
                    </div>

                    {{-- Nevigate Button --}}
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true"
                            style="width: 10px; height: 10px;"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true"
                            style="width: 10px; height: 10px;"></span>
                    </button>
                </div>

                {{-- Thamline Image --}}
                <div class="d-flex mt-3 gap-2 overflow-auto pb-2 custom-scrollbar">
                    @php $imgIndex = 0; @endphp

                    {{-- Main Image --}}
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                            class="img-thumbnail rounded-3 thumb-img active-thumb"
                            style="width: 80px; aspect-ratio: 1/1; cursor: pointer; object-fit: cover;"
                            onclick="new bootstrap.Carousel('#productCarousel').to({{ $imgIndex++ }})">
                    @endif

                    {{--Gallery Image--}}
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail rounded-3 thumb-img"
                            style="width: 80px; aspect-ratio: 1/1; cursor: pointer; object-fit: cover;"
                            onclick="new bootstrap.Carousel('#productCarousel').to({{ $imgIndex++ }})">
                    @endforeach
                </div>
            </div>

            {{-- Product Information --}}
            <div class="col-lg-6 col-md-12 ps-lg-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                class="text-secondary text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active fw-semibold text-dark">
                            {{ $product->category->name ?? 'Collection' }}
                        </li>
                    </ol>
                </nav>

                <h1 class="fw-bold text-dark mb-1" style="font-size: 1.75rem;">
                    {{ $product->name }}
                </h1>

                <div class="d-flex align-items-center mb-3">
                    <span class="text-muted me-2">Color:</span>
                    <span class="fw-semibold text-dark">{{ $product->variants->first()->color->name ?? 'Default' }}</span>
                    @if(isset($product->variants->first()->color->code))
                        <div class="ms-2 rounded-circle border shadow-sm"
                            style="width: 20px; height: 20px; background: {{ $product->variants->first()->color->code }}"></div>
                    @endif
                </div>

                <div class="mb-3">
                    <h2 class="text-danger fw-bold mb-0" id="current-price">
                        ৳ {{ number_format($product->variants->first()->sale_price ?? 0, 0) }}
                    </h2>
                </div>

                {{--Size and Stock Section--}}
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase small mb-3">Select Size:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($product->variants as $variant)
                            <div class="size-wrapper">
                                <input type="radio" class="btn-check" name="productSize" id="v_{{ $variant->id }}"
                                    value="{{ optional($variant->size)->name }}" data-variant-id="{{ $variant->id }}"
                                    data-price="{{ $variant->sale_price }}" data-stock="{{ $variant->stock }}" {{ $variant->stock <= 0 ? 'disabled' : '' }}>

                                <label class="btn btn-outline-dark px-4 py-2 fw-medium rounded-3" for="v_{{ $variant->id }}">
                                    {{ optional($variant->size)->name ?? 'N/A' }}
                                    @if($variant->stock <= 0)
                                        <small class="d-block text-danger" style="font-size: 10px;">Out of Stock</small>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    {{--Avaiable Quantity--}}
                    <div id="variantStockAlert" class="mt-3 small fw-bold"></div>
                </div>

                {{--Action Button --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <button id="addBtn" onclick="handleAddToCart(false)"
                            class="btn btn-dark btn-lg w-100 py-3 fw-bold rounded-pill shadow-sm">
                            ADD TO CART
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('checkout') }}"
                            class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold rounded-pill">
                            BUY IT NOW
                        </a>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-5 pt-4 border-top">
                    <h5 class="fw-bold mb-3 text-uppercase small">Description</h5>
                    <div class="text-muted fs-6" style="line-height: 1.8;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Size Selection and price Update Model
        document.querySelectorAll('input[name="productSize"]').forEach(input => {
            input.addEventListener('change', function () {
                const stock = parseInt(this.getAttribute('data-stock'));
                const price = parseFloat(this.getAttribute('data-price'));

                // Price Text Update
                document.getElementById('current-price').innerText = `৳ ${price.toLocaleString()}`;

                // Stock alert Update
                const alertBox = document.getElementById('variantStockAlert');
                if (stock > 0) {
                    alertBox.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i> Available: ${stock} items in stock</span>`;
                } else {
                    alertBox.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i> This size is out of stock!</span>`;
                }
            });
        });

        function handleAddToCart(isBuyNow) {
            const selected = document.querySelector('input[name="productSize"]:checked');
            if (!selected) {
                alert('Please select a size first!');
                return;
            }

            const variantId = selected.getAttribute('data-variant-id');
            const stock = parseInt(selected.getAttribute('data-stock'));

            if (stock <= 0) {
                alert('Sorry, this variant is out of stock!');
                return;
            }

            //AJAX Call
            console.log("Adding Variant ID:", variantId);
            alert("Product added to cart!");
            if (isBuyNow) window.location.href = "{{ route('cart.index') }}";
        }
    </script>
@endsection

@section('css')
    <style>
        .product-main-img {
            aspect-ratio: 3 / 4;
            width: 100%;
            object-fit: cover;
            background-color: #f8f9fa;
        }

        .thumb-img {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumb-img:hover,
        .active-thumb {
            border-color: #212529;
            opacity: 0.8;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .size-wrapper .btn-check:checked+label {
            background-color: #212529 !important;
            color: #fff !important;
            border-color: #212529 !important;
        }
    </style>
@endsection