@extends('frontend.layout.master')

@section('title', 'Home')
@section('content')
<div class="container mt-5">

    <div id="topCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/products/1.webp') }}" class="d-block w-100" alt="Slide 1"
                    style="height: 400px; object-fit: cover;">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('assets/images/products/2.webp') }}" class="d-block w-100" alt="Slide 2"
                    style="height: 400px; object-fit: cover;">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('assets/images/products/3.webp') }}" class="d-block w-100" alt="Slide 3"
                    style="height: 400px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/products/4.webp') }}" class="d-block w-100" alt="Slide 3"
                    style="height: 400px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/products/5.jpg') }}" class="d-block w-100" alt="Slide 3"
                    style="height: 400px; object-fit: cover;">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#topCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#topCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    {{-- Banner Section Fix --}}
    <section class="p-5 rounded-3 text-center text-white d-flex align-items-center justify-content-center mt-5"
        style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('assets/images/banner.jpg') }}'); background-size: cover; background-position: center; min-height: 400px;">

        <div style="background-color: rgba(0,0,0,0.45); padding: 40px; border-radius: 12px; width: 100%;">
            <h1 class="display-4 fw-bold mb-3">NEXT Fashion</h1>
            <p class="lead mb-4">
                Export Quality Clothing | COD Available | Nationwide Delivery
            </p>
            <a href="{{ url('/shop') }}" class="btn btn-light btn-lg text-danger fw-bold">
                Shop Now
            </a>
        </div>
    </section>
</div>
</section>

<section class="mt-5 px-3">
    <h2 class="fw-bold mb-4 text-center">Popular Products</h2>

    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/images/bag.avif') }}" class="card-img-top" alt="Men's Tshirt"
                    style="height: 150px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">Men's Tshirt</h5>
                    <p class="text-danger fw-bold">$49.99</p>
                    <a href="#" class="btn btn-danger btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/images/men.avif') }}" class="card-img-top" alt="Men's Jeans"
                    style="height: 150px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">Men's Jeans</h5>
                    <p class="text-danger fw-bold">$59.99</p>
                    <a href="#" class="btn btn-danger btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/images/men_tshirt.avif') }}" class="card-img-top" alt="Men's Jeans"
                    style="height: 150px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">Men's Jeans</h5>
                    <p class="text-danger fw-bold">$59.99</p>
                    <a href="#" class="btn btn-danger btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/images/watch.avif') }}" class="card-img-top" alt="Men's Jeans"
                    style="height: 150px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">Men's Jeans</h5>
                    <p class="text-danger fw-bold">$59.99</p>
                    <a href="#" class="btn btn-danger btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="daily-deals mt-5 ">
    <div class="deals-header d-flex justify-content-between align-items-center mb-3">
        <h3>Daily Deals</h3>
        <div class="countdown fw-bold">
            <span>11</span> : <span>28</span> : <span>38</span>
        </div>
        <a href="#" class="see-more text-decoration-none">See More →</a>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        <div class="col">
            <div class="card deal-card h-100 border-0 shadow-sm p-2">
                <span class="badge bg-warning text-dark position-absolute m-2">Save ৳ 801</span>
                <img src="{{ asset('assets/images/bag.avif') }}" class="card-img-top" alt="Bag">
                <div class="card-body">
                    <h6 class="card-title">Laptop And Travelling Minimalist Backpack</h6>
                    <div class="price text-danger fw-bold">৳ 699 <del class="text-muted">৳ 1500</del></div>
                    <div class="available small mt-1">Available : <b>4479</b></div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card deal-card h-100 border-0 shadow-sm p-2">
                <span class="badge bg-warning text-dark position-absolute m-2">Save ৳ 2420</span>
                <img src="{{ asset('assets/images/watch2.avif') }}" class="card-img-top" alt="Cooker">
                <div class="card-body">
                    <h6>Miyako 2000 Watt Infrared Cooker</h6>
                    <div class="price text-danger fw-bold">৳ 4080 <del class="text-muted">৳ 6500</del></div>
                    <div class="available small mt-1">Available : <b>3</b></div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card deal-card h-100 border-0 shadow-sm p-2">
                <span class="badge bg-warning text-dark position-absolute m-2">Save ৳ 1080</span>
                <img src="{{ asset('assets/images/men.avif') }}" class="card-img-top" alt="Watch">
                <div class="card-body">
                    <h6>Fastrack Quartz Analog Watch</h6>
                    <div class="price text-danger fw-bold">৳ 3420 <del class="text-muted">৳ 4500</del></div>
                    <div class="available small mt-1">Available : <b>2</b></div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card deal-card h-100 border-0 shadow-sm p-2">
                <span class="badge bg-warning text-dark position-absolute m-2">Save ৳ 550</span>
                <img src="{{ asset('assets/images/watch.avif') }}" class="card-img-top" alt="Air Fryer">
                <div class="card-body">
                    <h6>Miyako Air Fryer Rapid Technology</h6>
                    <div class="price text-danger fw-bold">৳ 4200 <del class="text-muted">৳ 4750</del></div>
                    <div class="available small mt-1">Available : <b>30</b></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row text-center mt-5">
    <div class="col-md-4 mb-4">
        <div class="p-4 bg-light rounded shadow-sm h-100">
            <h5 class="fw-bold mb-2">Premium Quality</h5>
            <p class="text-muted">Carefully selected fabrics for ultimate comfort and style.</p>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="p-4 bg-light rounded shadow-sm h-100">
            <h5 class="fw-bold mb-2">Fast Delivery</h5>
            <p class="text-muted">Nationwide delivery with COD option available for your convenience.</p>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="p-4 bg-light rounded shadow-sm h-100">
            <h5 class="fw-bold mb-2">Best Offers</h5>
            <p class="text-muted">Enjoy exclusive discounts on trending fashion products.</p>
        </div>
    </div>
</section>

</div>
@endsection

@section('styles')
{{-- Home.css এর কন্টেন্ট এখানে বা আলাদা ফাইলে রাখতে পারেন --}}
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endsection