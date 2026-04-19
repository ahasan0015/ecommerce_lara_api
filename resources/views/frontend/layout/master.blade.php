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
    
    @yield('css')
</head>

<body>

    <div class="d-flex flex-column min-vh-100">
        {{-- Navbar (React:
        <Navbar />) --}}
        @include('frontend.layout.partials.navbar')

        {{-- Main content (React:
        <Outlet />) --}}
        <main class="flex-fill">
            @yield('content')
        </main>

        {{-- Footer (React:
        <Footer />) --}}
        @include('frontend.layout.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // এটি master.blade.php তে থাকবে
        document.addEventListener('DOMContentLoaded', function () {
            updateCartBadge(); // পেজ লোড হলেই ব্যাজ আপডেট হবে
        });

        function updateCartBadge(newCount = null) {
            const cartBadge = document.getElementById('cart-count');

            if (!cartBadge) {
                console.error("Cart badge element not found! Make sure id='cart-count' exists in navbar.");
                return;
            }

            //login user AJAX
            if (newCount !== null) {
                cartBadge.innerText = newCount;
                return;
            }

            // default loading
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (!isLoggedIn) {
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                let totalCount = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
                cartBadge.innerText = totalCount;
            }
        }
    </script>

    @yield('js')
</body>

</html>