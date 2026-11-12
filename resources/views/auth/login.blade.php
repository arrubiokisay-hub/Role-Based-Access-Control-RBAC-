<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        body {
            background: #212529;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">USER PORTAL</h2>
            <p class="text-muted small">Barangay Management System</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.login.submit') }}">
            @csrf
            <div class="mb-3">
                <input
                    type="text"
                    name="username"
                    class="form-control form-control-lg"
                    placeholder="Username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    required
                >
            </div>
            <div class="mb-4">
                <input
                    type="password"
                    name="password"
                    class="form-control form-control-lg"
                    placeholder="Password"
                    autocomplete="current-password"
                    required
                >
            </div>
            <button type="submit"
                    class="btn btn-dark btn-lg w-100 fw-bold">
                SECURE LOGIN
            </button>
        </form>

        {{-- ✅ NEW: Sign Up Link --}}
        <div class="text-center mt-3">
            <span class="text-muted small">Don't have an account?</span>
            <a href="{{ route('user.register') }}"
               class="small fw-bold text-decoration-none ms-1">
                Sign Up
            </a>
        </div>

        <div class="text-center mt-2">
            <a href="{{ route('portal') }}"
               class="text-muted small text-decoration-none">
                Back to Portal
            </a>
        </div>
    </div>
</body>
</html>