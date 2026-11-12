<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact System Administrator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .contact-card {
            background: white;
            padding: 45px 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .contact-icon {
            width: 64px;
            height: 64px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #0d6efd;
        }
        .contact-item:hover {
            background-color: #f8f9fa;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
    </style>
</head>
<body>
    @php
        // Get the first admin from your database
        $admin = DB::table('users')->where('role', 'admin')->first();
    @endphp

    <div class="contact-card">
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center mb-3">
                <i class="fa-solid fa-shield-halved text-primary" style="font-size: 40px;"></i>
            </div>
            <h2 class="fw-bold text-dark mb-1">Contact System Administrator</h2>
            <p class="text-muted small">Barangay Management System — Admin Access Requests</p>
        </div>

        <p class="text-muted small mb-4 text-center">
            If you need administrator privileges or encounter issues accessing the system,
            please reach out using the information below.
        </p>

        {{-- Email — uses username as email --}}
        <a href="mailto:{{ $admin->username ?? 'admin@barangay-system.com' }}" class="text-decoration-none text-dark">
            <div class="d-flex align-items-center gap-3 p-3 contact-item mb-2">
                <div class="contact-icon flex-shrink-0">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <div class="fw-semibold">Email Address</div>
                    <div class="text-muted small">{{ $admin->username ?? 'admin@barangay-system.com' }}</div>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-muted ms-auto small"></i>
            </div>
        </a>

        {{-- Name --}}
        <div class="d-flex align-items-center gap-3 p-3 mb-2">
            <div class="contact-icon flex-shrink-0">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <div class="fw-semibold">System Administrator</div>
                <div class="text-muted small">{{ $admin->name ?? 'Not specified — edit admin record to add' }}</div>
            </div>
        </div>

        {{-- Phone — add column first --}}
        <div class="d-flex align-items-center gap-3 p-3 mb-2">
            <div class="contact-icon flex-shrink-0">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <div class="fw-semibold">Contact Number</div>
                <div class="text-muted small">
                    @if(!empty($admin->phone))
                        {{ $admin->phone }}
                    @else
                        <span class="text-warning">Not yet added — see note below</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Office Hours --}}
        <div class="d-flex align-items-center gap-3 p-3">
            <div class="contact-icon flex-shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="fw-semibold">Office Hours</div>
                <div class="text-muted small">Monday – Friday, 8:00 AM – 5:00 PM</div>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-grid">
            <a href="{{ route('admin.login') }}" class="btn btn-dark fw-bold py-2 mb-2">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Admin Login
            </a>
            <a href="{{ route('portal') }}" class="btn btn-light text-decoration-none">
                ← Return to Main Portal
            </a>
        </div>
    </div>
</body>
</html>