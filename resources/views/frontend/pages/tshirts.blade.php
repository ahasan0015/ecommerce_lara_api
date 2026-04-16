@extends('frontend.layout.master')

@section('title', 'T-Shirt Collection')

@section('content')
<section class="container my-5">
    <h2 class="text-center fw-bold mb-4">
        T-Shirt Collection
    </h2>

    <div class="row g-4">
        {{-- কন্ট্রোলার থেকে আসা $products ভেরিয়েবল ব্যবহার করা হয়েছে --}}
        @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 product-card">
                    
                    @php
                        // প্রথম ভ্যারিয়েন্ট এবং তার প্রথম ইমেজ বের করা হচ্ছে
                        $firstVariant = $product->variants->first();
                        $imageName = $firstVariant && $firstVariant->images->first() 
                                    ? $firstVariant->images->first()->image 
                                    : null;
                    @endphp

                    <img 
                        src="{{ $imageName ? asset('storage/' . $imageName) : asset('assets/images/no-image.jpg') }}" 
                        alt="{{ $product->name }}" 
                        class="card-img-top"
                        style="height: 280px; object-fit: cover;"
                    >

                    <div class="card-body text-center">
                        {{-- প্রোডাক্টের নাম --}}
                        <h5 class="card-title h6 fw-bold">{{ $product->name }}</h5>
                        
                        {{-- ক্যাটাগরির নাম (Mens Panjabi বা আপনার ক্যাটাগরি) --}}
                        <p class="text-muted small mb-2">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        
                        <h6 class="fw-bold text-danger">
                            {{-- আপনার Tinker এ sale_price ছিল --}}
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
        @endforeach
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