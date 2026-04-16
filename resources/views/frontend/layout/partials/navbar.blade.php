<nav class="navbar navbar-expand-lg shadow-sm navbar-gradient">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">
            Ayesha Fashion
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#fashionNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="fashionNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                        Men
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('tshirts.page') }}">T-Shirts</a></li>
                        <li><a class="dropdown-item" href="{{ url('/men/shirts') }}">Shirts</a></li>
                        <li><a class="dropdown-item" href="{{ url('/men/pants') }}">Pants</a></li>
                        <li><a class="dropdown-item" href="{{ url('/men/shoes') }}">Shoes</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                        Women
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('/women/dresses') }}">Dresses</a></li>
                        <li><a class="dropdown-item" href="{{ url('/women/tops') }}">Tops</a></li>
                        <li><a class="dropdown-item" href="{{ url('/women/pants') }}">Pants</a></li>
                        <li><a class="dropdown-item" href="{{ url('/women/shoes') }}">Shoes</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="{{ url('/new-arrivals') }}">
                        New Arrivals
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <form class="d-flex" action="{{ url('/search') }}" method="GET">
                        <input class="form-control form-control-sm me-2" type="search" name="query" placeholder="Search products" />
                    </form>
                </li>

                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light fw-bold px-3 rounded text-white" href="{{ url('/cart') }}">
                        🛒 Cart
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                        👤 My Account
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @guest
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ url('/profile') }}">My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>