<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextFashion | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            font-family: 'Inter', sans-serif;
        }
        .login-container {
            min-vh-100;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            overflow: hidden;
        }
        .card-header-brand {
            background-color: #198754; /* Success Green */
            color: white;
            padding: 30px;
            text-align: center;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
            border-color: #198754;
        }
        .btn-login {
            background-color: #198754;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #146c43;
            transform: translateY(-1px);
        }
        .forgot-link {
            font-size: 0.875rem;
            color: #6c757d;
            text-decoration: none;
        }
        .forgot-link:hover {
            color: #198754;
        }
    </style>
</head>
<body>

<div class="container login-container mt-5 mb-5">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="card login-card">
                <div class="card-header-brand">
                    <h2 class="fw-bold mb-0">NextFashion</h2>
                    <p class="small mb-0 opacity-75">Welcome back! Please login to your account.</p>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" id="email" name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="forgot-link" href="{{ route('password.request') }}">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <input type="password" id="password" name="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                required placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label text-muted small" for="remember_me">Remember me on this device</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-login">
                                Log in
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Create Account</a>
                        </p>
                    </div>
                </div>
            </div>
            <p class="text-center text-muted mt-4 small">&copy; {{ date('Y') }} NextFashion. All rights reserved.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>