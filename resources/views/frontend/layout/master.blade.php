<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') | My Clothing Store</title>

    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="পছন্দের সব এক্সক্লুসিভ ক্লোথিং আইটেম কিনুন সেরা দামে।">
    <meta property="og:image" content="@yield('og_image', asset('default-logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

    <style>
        .product-card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        footer {
            background: #f8f9fa;
            padding: 40px 0;
        }
    </style>
    <!-- @stack('css') -->
    @yield('styles')
</head>

<body>

    <div class="d-flex flex-column min-vh-100">
        {{-- Navbar (React: <Navbar />) --}}
        @include('frontend.layout.partials.navbar')

        {{-- Main content (React: <Outlet />) --}}
        <main class="flex-fill">
            @yield('content')
        </main>

        {{-- Footer (React: <Footer />) --}}
        @include('frontend.layout.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateCartBadge() {
        const cartBadge = document.getElementById('cart-count');
        if (!cartBadge) return; // if not cart badge funtion closed

        // সঠিক ব্লেড সিনট্যাক্স (Extra { } রিমুভ করা হয়েছে)
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        if (!isLoggedIn) {
            // গেস্ট ইউজারের জন্য লোকাল স্টোরেজ থেকে কাউন্ট আপডেট
            let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            let totalCount = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
            cartBadge.innerText = totalCount;
        } else {
            // লগইন ইউজারের ক্ষেত্রে location.reload() সরিয়ে দেওয়া হয়েছে।
            // কারণ লগইন থাকলে লারাভেল নিজে থেকেই নেভবার রেন্ডার করার সময় কাউন্ট বসিয়ে দেয়।
            console.log("User logged in: Database count managed by Laravel.");
        }
    }

    // পেজ লোড হওয়ার সময় কাউন্ট চেক করবে
    document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>

    @yield('js')
</body>

</html>