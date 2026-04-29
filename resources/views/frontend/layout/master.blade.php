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
    {{-- //sweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        document.addEventListener('DOMContentLoaded', function () {
            updateCartBadge(); 
        });

        function updateCartBadge() {
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            let totalItems = 0;

            if (!isLoggedIn) {
                // guest LocalStorage 
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity), 0);

                // desktop and mobile badge update
                const desktopBadge = document.getElementById('cart-count');
                const mobileBadge = document.getElementById('cart-count-mobile');

                if (desktopBadge) desktopBadge.innerText = totalItems;
                if (mobileBadge) mobileBadge.innerText = totalItems;
            }
        }

        document.addEventListener('DOMContentLoaded', updateCartBadge);
    </script>

    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Order Placed!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif


    @yield('js')
</body>

</html>