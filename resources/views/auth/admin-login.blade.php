<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login</title>
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
        a.contact-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        a.contact-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">ADMIN PORTAL</h2>
            <p class="text-muted small">Barangay Management System</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <input
                    type="text"
                    name="username"
                    class="form-control form-control-lg"
                    placeholder="Admin Username"
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

        {{-- ✅ Clickable Contact Link --}}
        <div class="text-center mt-3">
            <span class="text-muted small">Need admin access?</span>
            <a href="{{ route('contact.admin') }}" class="contact-link ms-1">
    Contact System Administrator
</a
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