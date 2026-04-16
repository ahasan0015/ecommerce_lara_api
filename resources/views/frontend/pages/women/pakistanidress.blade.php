@extends('frontend.layout.master')

@section('title', 'Pakistani Dress Collection')

@section('content')
<section class="container my-5">
    <h2 class="text-center fw-bold mb-4">
        {{ $products->first()->category->name ?? 'Pakistani Dress Collection' }}
    </h2>

    <div class="row g-4">
        {{-- কন্ট্রোলার থেকে আসা $products ভেরিয়েবল লুপ করা হচ্ছে --}}
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 product-card">
                    
                    {{-- পাঞ্জাবি পেজের মতো ইমেজ লজিক এখানে দেওয়া হলো --}}
                    @php
                        $firstVariant = $product->variants->first();
                        // মেইন ইমেজ চেক করা হচ্ছে, না থাকলে প্রথম ইমেজ নেওয়া হচ্ছে
                        $mainImage = optional($firstVariant)->images->where('is_main', 1)->first()
                                     ?? optional($firstVariant)->images->first();
                        
                        // পাথ হিসেবে storage/ ব্যবহার করা হয়েছে আপনার পাঞ্জাবি পেজের মতো
                        $imagePath = $mainImage 
                                     ? asset('storage/' . $mainImage->image) 
                                     : asset('assets/images/placeholder.jpg');
                    @endphp

                    <img src="{{ $imagePath }}" alt="{{ $product->product_name ?? $product->name }}" class="card-img-top" style="height: 280px; object-fit: cover;">

                    <div class="card-body text-center">
                        {{-- প্রোডাক্টের নাম --}}
                        <h5 class="card-title h6 fw-bold mb-1">{{ $product->product_name ?? $product->name }}</h5>
                        
                        {{-- SKU --}}
                        <p class="text-muted small mb-1">
                            SKU: <span class="fw-semibold">{{ $firstVariant->sku ?? 'N/A' }}</span>
                        </p>

                        <p class="text-muted small mb-2">Exclusive Pakistani Dress</p>

                        {{-- প্রাইস (পাঞ্জাবি পেজের মতো sale_price ব্যবহার করা হয়েছে) --}}
                        <h6 class="fw-bold text-danger">
                            ৳ {{ number_format($firstVariant->sale_price ?? 0, 0) }}
                        </h6>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3">
                        <form action="#" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-dark w-100 rounded-pill">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center my-5">
                <h4 class="text-muted">No Pakistani Dress found in this collection.</h4>
            </div>
        @endforelse
    </div>
</section>
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