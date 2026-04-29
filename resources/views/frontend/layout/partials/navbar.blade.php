{{-- ================= DESKTOP NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-gradient shadow-sm d-none d-lg-flex">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            Ayesha Fashion
        </a>

        <div class="collapse navbar-collapse">

            {{-- LEFT MENU --}}
            <ul class="navbar-nav me-auto">

                {{-- MEN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Men</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('tshirts.index') }}">T-Shirts</a></li>
                        <li><a class="dropdown-item" href="{{ route('shirts.page') }}">Shirts</a></li>
                        <li><a class="dropdown-item" href="{{ route('pant.page') }}">Pants</a></li>
                        <li><a class="dropdown-item" href="{{ route('panjabi.index') }}">Panjabi</a></li>
                    </ul>
                </li>

                {{-- WOMEN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">Women</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('pakistani.dress') }}">Pakistani Dress</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/new-arrivals') }}">New Arrivals</a>
                </li>

            </ul>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                {{-- SEARCH --}}
                <li>
                    <form class="d-flex" action="{{ url('/search') }}">
                        <input class="form-control form-control-sm" name="query" placeholder="Search">
                    </form>
                </li>

                {{-- CART --}}
                <li>
                    <a class="btn btn-outline-light position-relative" href="{{ route('cart.index') }}">
                        🛒
                        <span id="cart-count"
                            class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                            @if(Auth::check())
                                {{ Auth::user()->cart?->items->sum('quantity') ?? 0 }}
                            @else
                                0
                            @endif
                        </span>
                    </a>
                </li>

                {{-- ACCOUNT --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">👤</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @guest
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ url('/profile') }}">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>


{{-- ================= MOBILE TOP ================= --}}
<nav class="mobile-top-navbar d-lg-none">

    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold fs-5">Ayesha Fashion</div>
        <a href="{{ url('/search') }}">🔍</a>
    </div>

    {{-- HORIZONTAL CATEGORY --}}
    <div class="category-scroll">

        <div class="category-item active" onclick="openTab('men', this)">Mens</div>
        <div class="category-item" onclick="openTab('women', this)">Womens</div>
        <div class="category-item" onclick="openTab('kids', this)">Kids</div>

    </div>

    {{-- SUBMENU AREA --}}
    <div class="submenu-container">

        {{-- MEN --}}
        <div class="submenu-content" id="men">

            <a href="{{ route('tshirts.index') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/men-tshirt.avif') }}" alt="">
                <span>T-Shirts</span>
            </a>

            <a href="{{ route('shirts.page') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/mens_casual_shirt.avif') }}" alt="">
                <span>Shirts</span>
            </a>

            <a href="{{ route('pant.page') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/men_jeans.avif') }}" alt="">
                <span>Pants</span>
            </a>

            <a href="{{ route('panjabi.index') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/mens_panjabi.avif') }}" alt="Panjabi">
                <span>Panjabi</span>
            </a>
        </div>

        {{-- WOMEN --}}
        <div class="submenu-content" id="women">
            {{-- <a href="{{ route('pakistani.dress') }}">Pakistani Dress</a> --}}
            <a href="{{ route('pakistani.dress') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/men-tshirt.avif') }}" alt="">
                <span>Pakistani Dress</span>
            </a>

            <a href="{{ route('shirts.page') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/mens_casual_shirt.avif') }}" alt="">
                <span>Kamiz</span>
            </a>

            <a href="{{ route('pant.page') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/men_jeans.avif') }}" alt="">
                <span>Jackets</span>
            </a>

            <a href="{{ route('panjabi.index') }}" class="circle-item">
                <img src="{{ asset('assets/images/category/mens_panjabi.avif') }}" alt="Panjabi">
                <span>Kurti</span>
            </a>
        </div>

        {{-- KIDS --}}
        <div class="submenu-content" id="kids">
            <a href="#">Boys</a>
            <a href="#">Girls</a>
        </div>

    </div>

</nav>


{{-- ================= MOBILE BOTTOM ================= --}}
<nav class="mobile-bottom-navbar d-lg-none">

    <a href="{{ url('/') }}">
        <div>🏠</div>
        <small>Home</small>
    </a>

    <a href="#">
        <div>📂</div>
        <small>Category</small>
    </a>

    <a href="#">
        <div>🔥</div>
        <small>Deals</small>
    </a>

    <a href="{{ route('cart.index') }}" class="position-relative">
        <div>🛒</div>
        <small>Cart</small>
        <span id="cart-count-mobile" class="cart-badge">
            {{ Auth::check() ? (Auth::user()->cart?->items->sum('quantity') ?? 0) : 0 }}
        </span>
    </a>

    <a href="{{ url('/profile') }}">
        <div>👤</div>
        <small>Account</small>
    </a>

</nav>

<script>
    // পেজ লোড হওয়ার পর চেক করবে আগে কোন ট্যাব সিলেক্ট করা ছিল
    document.addEventListener("DOMContentLoaded", function () {
        // localStorage থেকে সেভ করা আইডি আনবে, না থাকলে default 'men' নিবে
        const activeTabId = localStorage.getItem('activeMobileTab') || 'men';

        // ওই আইডির জন্য ট্যাব এলিমেন্টটি খুঁজে বের করবে
        const activeTabElement = document.querySelector(`.category-item[onclick*="'${activeTabId}'"]`);

        if (activeTabElement) {
            openTab(activeTabId, activeTabElement, false);
        }
    });

    function openTab(tabId, element, shouldSave = true) {
        // সব ট্যাব থেকে active ক্লাস রিমুভ করা
        document.querySelectorAll('.category-item').forEach(el => {
            el.classList.remove('active');
        });

        // সব সাবমেনু কন্টেন্ট হাইড করা
        document.querySelectorAll('.submenu-content').forEach(el => {
            el.classList.remove('active');
        });

        // ক্লিক করা ট্যাব এবং তার কন্টেন্ট শো করা
        element.classList.add('active');
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.add('active');
        }

        // ব্রাউজারের মেমরিতে সেভ করে রাখা যাতে রিফ্রেশ দিলেও না যায়
        if (shouldSave) {
            localStorage.setItem('activeMobileTab', tabId);
        }
    }
</script>