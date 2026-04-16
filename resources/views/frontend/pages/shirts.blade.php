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
                    <img 
                        src="{{ asset('assets/images/products/tshirt/' . $item['image']) }}" 
                        alt="{{ $item['name'] }}" 
                        class="card-img-top"
                        style="height: 280px; object-fit: cover;"
                    >

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