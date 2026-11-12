<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Found Item - Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .btn-round { border-radius: 50px; }
    </style>
</head>
<body>

{{-- Navbar — same as your user dashboard --}}
<nav class="navbar navbar-dark bg-dark px-4 py-3">
    <div class="container-fluid">
        <a href="{{ route('user.dashboard') }}" class="navbar-brand fw-bold">
            <i class="fa-solid fa-box-open me-2"></i> LOST & FOUND
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-light">Welcome, {{ session('username') }}</span>
            <form action="{{ route('logout') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to log out?');">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 text-center">
                    <h3 class="fw-bold mb-0">
                        <i class="fa-solid fa-hand-holding-heart me-2"></i>Report Found Item
                    </h3>
                </div>

                <div class="card-body p-4">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.report.found.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">What did you find?</label>
                            <input type="text" name="item_name" class="form-control border-primary-subtle"
                                   placeholder="e.g. Black Wallet" value="{{ old('item_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Where was it found?</label>
                            <input type="text" name="location" class="form-control border-primary-subtle"
                                   placeholder="e.g. Basketball Court" value="{{ old('location') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date Found</label>
                            <input type="date" name="date_happened" class="form-control"
                                   value="{{ old('date_happened') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control border-primary-subtle" rows="3"
                                      placeholder="Describe the item...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Upload Item Photo (Optional)</label>
                            <input type="file" name="item_image" class="form-control border-primary-subtle"
                                   accept="image/*">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Your Contact Number</label>
                            <input type="text" name="contact" id="contactInput"
                                   class="form-control border-primary-subtle @error('contact') is-invalid @enderror"
                                   placeholder="09XXXXXXXXX" value="{{ old('contact') }}"
                                   pattern="09[0-9]{9}" maxlength="11" required
                                   title="Must start with 09 and be 11 digits total.">
                            <div class="form-text text-muted small">Must be exactly 11 digits.</div>
                            <div id="contactWarning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                Invalid format: Must start with 09 and have 11 digits.
                            </div>
                        </div>

                        <script>
                            function validateContact(input) {
                                input.value = input.value.replace(/[^0-9]/g, '');
                                const warning = document.getElementById('contactWarning');
                                const pattern = /^09[0-9]{9}$/;

                                if (input.value.length > 0 && !pattern.test(input.value)) {
                                    warning.style.display = 'block';
                                    input.classList.add('is-invalid');
                                } else {
                                    warning.style.display = 'none';
                                    input.classList.remove('is-invalid');
                                }
                            }
                            document.getElementById('contactInput').addEventListener('input', function() {
                                validateContact(this);
                            });
                        </script>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-round py-2 fw-bold shadow-sm">
                                Submit Found Notice
                            </button>
                            <a href="{{ route('user.dashboard') }}"
                               class="btn btn-link text-muted text-decoration-none small">
                                Go Back
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