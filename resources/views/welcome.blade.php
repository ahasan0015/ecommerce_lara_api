@extends('frontend.layout.master')


@section('title', 'Home | NEXT Fashion')

@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
    {{--
    মেইন র‍্যাপার: overflow-hidden ব্যবহার করা হয়েছে যাতে ডানে-বামে বাড়তি স্ক্রলবার না আসে।
    --}}
    <div class="main-wrapper overflow-hidden">

        {{-- 1. Hero Carousel Section --}}
        <div class="container mt-4">
            <div id="topCarousel" class="carousel slide shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel"
                data-bs-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('assets/images/products/1.webp') }}" class="d-block w-100" alt="Slide 1"
                            style="height: 450px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/products/2.webp') }}" class="d-block w-100" alt="Slide 2"
                            style="height: 450px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/products/3.webp') }}" class="d-block w-100" alt="Slide 3"
                            style="height: 450px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/products/4.webp') }}" class="d-block w-100" alt="Slide 4"
                            style="height: 450px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/products/5.jpg') }}" class="d-block w-100" alt="Slide 5"
                            style="height: 450px; object-fit: cover;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#topCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#topCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        {{-- 2. Promotional Banner Section --}}
        <div class="container mt-5">
            <section
                class="p-5 rounded-4 text-center text-white d-flex align-items-center justify-content-center shadow-lg promotional-banner"
                style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('assets/images/banner.jpg') }}') center/cover no-repeat; min-height: 400px;">
                <div class="banner-content p-4"
                    style="background: rgba(0,0,0,0.3); border-radius: 20px; backdrop-filter: blur(5px);">
                    <h1 class="display-3 fw-bold mb-3">NEXT Fashion</h1>
                    <p class="lead mb-4 fw-light">Export Quality Clothing | Cash on Delivery | Nationwide Fast Shipping</p>
                    <a href="{{ url('/shop') }}"
                        class="btn btn-light btn-lg px-5 py-3 text-danger fw-bold rounded-pill shadow-sm">
                        Shop Collection
                    </a>
                </div>
            </section>
        </div>

        {{-- 3. Popular Products Grid --}}
        <div class="container mt-5">
            <div class="section-title text-center mb-5">
                <h2 class="fw-bold display-6">Popular Products</h2>
                <div class="mx-auto bg-danger" style="height: 3px; width: 60px;"></div>
            </div>

            <div class="row g-4 mx-0">
                {{-- Product Item 1 --}}
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="{{ asset('assets/images/bag.avif') }}" class="card-img-top" alt="Product"
                            style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title h6">Premium Laptop Bag</h5>
                            <p class="text-danger fw-bold mb-3">৳ 1,200</p>
                            <a href="#" class="btn btn-outline-danger btn-sm rounded-pill w-100">Add to Cart</a>
                        </div>
                    </div>
                </div>
                {{-- Product Item 2 --}}
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="{{ asset('assets/images/men.avif') }}" class="card-img-top" alt="Product"
                            style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title h6">Export Quality Chinos</h5>
                            <p class="text-danger fw-bold mb-3">৳ 1,500</p>
                            <a href="#" class="btn btn-outline-danger btn-sm rounded-pill w-100">Add to Cart</a>
                        </div>
                    </div>
                </div>
                {{-- Product Item 3 --}}
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="{{ asset('assets/images/men_tshirt.avif') }}" class="card-img-top" alt="Product"
                            style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title h6">Cotton Slim Fit Tee</h5>
                            <p class="text-danger fw-bold mb-3">৳ 650</p>
                            <a href="#" class="btn btn-outline-danger btn-sm rounded-pill w-100">Add to Cart</a>
                        </div>
                    </div>
                </div>
                {{-- Product Item 4 --}}
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="{{ asset('assets/images/watch.avif') }}" class="card-img-top" alt="Product"
                            style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title h6">Classic Quartz Watch</h5>
                            <p class="text-danger fw-bold mb-3">৳ 2,200</p>
                            <a href="#" class="btn btn-outline-danger btn-sm rounded-pill w-100">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Daily Deals Section --}}
        <div class="container mt-5 mb-5">
            <div class="bg-white p-4 rounded-4 shadow-sm border overflow-hidden">
                <div class="deals-header d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <h3 class="fw-bold mb-0 me-3">🔥 Daily Deals</h3>
                        <div class="countdown-timer d-flex gap-2">
                            <span class="badge bg-danger p-2">11h</span>
                            <span class="badge bg-danger p-2">28m</span>
                            <span class="badge bg-danger p-2">38s</span>
                        </div>
                    </div>
                    <a href="#" class="btn btn-link text-decoration-none fw-bold text-danger">View All →</a>
                </div>

                <div class="row g-3 mx-0">
                    {{-- Item 1: Miyako Infrared Cooker --}}
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm p-2 position-relative h-100 deal-hover-card">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">-37%
                                OFF</span>
                            <img src="{{ asset('assets/images/watch2.avif') }}" class="card-img-top rounded" alt="Cooker"
                                style="height: 180px; object-fit: cover;">
                            <div class="card-body px-1 py-2">
                                <h6 class="text-truncate small fw-bold mb-1">Miyako 2000W Infrared Cooker</h6>
                                <div class="price text-danger fw-bold mb-0">৳ 4,080 <del class="text-muted small">৳
                                        6,500</del></div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 30%"></div>
                                </div>
                                <div class="available x-small mt-1 text-muted">Available: 3</div>
                            </div>
                        </div>
                    </div>

                    {{-- Item 2: Fastrack Watch --}}
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm p-2 position-relative h-100 deal-hover-card">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">-24%
                                OFF</span>
                            <img src="{{ asset('assets/images/men.avif') }}" class="card-img-top rounded" alt="Watch"
                                style="height: 180px; object-fit: cover;">
                            <div class="card-body px-1 py-2">
                                <h6 class="text-truncate small fw-bold mb-1">Fastrack Quartz Analog Watch</h6>
                                <div class="price text-danger fw-bold mb-0">৳ 3,420 <del class="text-muted small">৳
                                        4,500</del></div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 20%"></div>
                                </div>
                                <div class="available x-small mt-1 text-muted">Available: 2</div>
                            </div>
                        </div>
                    </div>

                    {{-- Item 3: Miyako Air Fryer --}}
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm p-2 position-relative h-100 deal-hover-card">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">-12%
                                OFF</span>
                            <img src="{{ asset('assets/images/watch.avif') }}" class="card-img-top rounded" alt="Air Fryer"
                                style="height: 180px; object-fit: cover;">
                            <div class="card-body px-1 py-2">
                                <h6 class="text-truncate small fw-bold mb-1">Miyako Air Fryer Rapid Tech</h6>
                                <div class="price text-danger fw-bold mb-0">৳ 4,200 <del class="text-muted small">৳
                                        4,750</del></div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 60%"></div>
                                </div>
                                <div class="available x-small mt-1 text-muted">Available: 30</div>
                            </div>
                        </div>
                    </div>

                    {{-- Item 4: Laptop Backpack --}}
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm p-2 position-relative h-100 deal-hover-card">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">-53%
                                OFF</span>
                            <img src="{{ asset('assets/images/bag.avif') }}" class="card-img-top rounded" alt="Bag"
                                style="height: 180px; object-fit: cover;">
                            <div class="card-body px-1 py-2">
                                <h6 class="text-truncate small fw-bold mb-1">Minimalist Laptop Backpack</h6>
                                <div class="price text-danger fw-bold mb-0">৳ 699 <del class="text-muted small">৳
                                        1,500</del></div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 85%"></div>
                                </div>
                                <div class="available x-small mt-1 text-muted">Available: 4479</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Trust Features --}}
    <div class="container-fluid bg-light py-5 mt-5 border-top">
        <div class="container">
            <div class="row text-center g-4 mx-0">
                <div class="col-md-4">
                    <div class="p-3 feature-box">
                        <div class="mb-3 text-danger"><i class="bi bi-patch-check fs-1"></i></div>
                        <h5 class="fw-bold">Premium Quality</h5>
                        <p class="text-muted small px-lg-4">Carefully selected fabrics for ultimate comfort and long-lasting
                            style.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 feature-box border-start border-end border-md-none">
                        <div class="mb-3 text-danger"><i class="bi bi-truck fs-1"></i></div>
                        <h5 class="fw-bold">Fast Delivery</h5>
                        <p class="text-muted small px-lg-4">Nationwide delivery with COD option available for your ease.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 feature-box">
                        <div class="mb-3 text-danger"><i class="bi bi-tag fs-1"></i></div>
                        <h5 class="fw-bold">Best Offers</h5>
                        <p class="text-muted small px-lg-4">Enjoy exclusive discounts on the latest trending fashion items.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@section('styles')
    <style>
        /* Global scrollbar fix */
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            background-color: #fdfdfd;
        }

        /* Animation & Hover Effects */
        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12) !important;
        }

        .deal-hover-card {
            transition: transform 0.3s ease;
        }

        .deal-hover-card:hover {
            transform: scale(1.02);
        }

        .carousel-item img {
            filter: brightness(0.9);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .display-3 {
                font-size: 2.2rem;
            }

            .carousel-item img {
                height: 280px !important;
            }

            .promotional-banner {
                padding: 30px 15px !important;
                min-height: 300px !important;
            }

            .feature-box {
                border: none !important;
                margin-bottom: 20px;
            }
        }
    </style>

    {{-- Custom CSS & Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection