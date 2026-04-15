<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<footer class="text-white pt-3 pb-4" style="background: linear-gradient(90deg, #ff6b6b, #f94d6a);">
    <div class="container">
        <div class="row">

            <div class="col-md-3 mb-1">
                <h5 class="fw-bold mb-2">NEXT Fashion</h5>
                <p>Trendy clothing and accessories delivered to your doorstep. Quality products, great prices, and fast shipping.</p>
                <div class="d-flex mt-2">
                    <a href="https://facebook.com" target="_blank" rel="noreferrer" class="text-white me-3">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noreferrer" class="text-white me-3">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://wa.me/8801580707730" target="_blank" rel="noreferrer" class="text-white me-3">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noreferrer" class="text-white me-3">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/shop') }}" class="text-white text-decoration-none">Shop</a></li>
                    <li><a href="{{ url('/new-arrivals') }}" class="text-white text-decoration-none">New Arrivals</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-white text-decoration-none">Contact Us</a></li>
                    <li><a href="{{ url('/about') }}" class="text-white text-decoration-none">About Us</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Customer Service</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/faq') }}" class="text-white text-decoration-none">FAQ</a></li>
                    <li><a href="{{ url('/shipping') }}" class="text-white text-decoration-none">Shipping & Returns</a></li>
                    <li><a href="{{ url('/privacy-policy') }}" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}" class="text-white text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-1">
                <h6 class="fw-bold mb-3">Contact</h6>
                <p>Email: support@nextfashion.com</p>
                <p>Phone: +8801580707730</p>
                <p>Address: 123 Fashion Street, Dhaka, Bangladesh</p>
            </div>

        </div>

        <hr class="bg-white mt-4">

        <div class="text-center mt-3">
            <span>© {{ date('Y') }} NEXT Fashion. All rights reserved.</span>
        </div>
    </div>
</footer>