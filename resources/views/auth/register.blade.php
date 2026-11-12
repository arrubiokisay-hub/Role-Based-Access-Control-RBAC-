<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .btn-round { border-radius: 50px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-4 text-center">
                    <h3 class="fw-bold mb-0">
                        <i class="fa-solid fa-user-plus me-2"></i>Create Account
                    </h3>
                    <p class="small opacity-75 mt-2 mb-0">Join the Lost & Found Community</p>
                </div>
                <div class="card-body p-4">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.register.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Juan Dela Cruz" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="juan123" value="{{ old('username') }}" required>
                            <div class="form-text small">Must be unique — min. 3 characters</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="At least 6 characters" required>
                            <div class="form-text small">Keep it secure!</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Type password again" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-round py-2 fw-bold shadow-sm">
                                <i class="fa-solid fa-check me-2"></i>Create Account
                            </button>
                            <a href="{{ route('user.login') }}"
                               class="btn btn-link text-decoration-none small text-center">
                                Already have an account? <strong>Sign In</strong>
                            </a>
                            <a href="{{ route('portal') }}"
                               class="btn btn-light text-muted text-decoration-none small">
                                ← Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>