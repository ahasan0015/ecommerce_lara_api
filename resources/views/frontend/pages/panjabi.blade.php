@extends('frontend.layout.master')

@section('title', 'Panjabi Collection')

@section('content')
<section class="container my-5">
    <h2 class="text-center fw-bold mb-4">
        {{ $products->first()->category->name ?? 'Panjabi Collection' }}
    </h2>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 product-card">

                    @php
                        $firstVariant = $product->variants->first();
                        $mainImage = optional($firstVariant)->images->where('is_main', 1)->first()
                                     ?? optional($firstVariant)->images->first();
                        $imagePath = $mainImage ? asset('storage/' . $mainImage->image) : asset('assets/images/placeholder.jpg');
                    @endphp

                    <img src="{{ $imagePath }}" alt="{{ $product->product_name }}" class="card-img-top" style="height: 280px; object-fit: cover;">

                    <div class="card-body text-center">
                        {{-- প্রোডাক্টের নাম --}}
                        <h5 class="card-title h6 fw-bold mb-1">{{ $product->product_name ?? $product->name }}</h5>
                        
                        {{-- SKU --}}
                        <p class="text-muted small mb-1">
                            SKU: <span class="fw-semibold">{{ $firstVariant->sku ?? 'N/A' }}</span>
                        </p>

                        <p class="text-muted small mb-2">Exclusive Panjabi</p>

                        {{-- প্রাইস --}}
                        <h6 class="fw-bold text-danger">
                            ৳ {{ number_format($firstVariant->sale_price ?? 0, 0) }}
                        </h6>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3">
                        <button class="btn btn-dark w-100 rounded-pill">Add to Cart</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>No Panjabi found in this collection.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection