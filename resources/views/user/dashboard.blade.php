
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard - Lost & Found</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .btn-round {
            border-radius: 50px;
        }

        .notice-card {
            transition: transform 0.2s ease;
        }

        .notice-card:hover {
            transform: translateY(-4px);
        }

        .item-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .pending-card {
            border: 2px dashed #dc3545 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4 py-3">
    <div class="container-fluid">
        <a href="{{ route('user.dashboard') }}"
           class="navbar-brand fw-bold">
            <i class="fa-solid fa-box-open me-2"></i>
            LOST & FOUND
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="text-light">
                Welcome, {{ session('username') }}
            </span>

            <form action="{{ route('logout') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to log out?');">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">

    {{-- Lost and Found actions --}}
    <div class="row g-4 mb-5 text-center">

        <div class="col-md-6">
            <div class="p-5 bg-danger text-white rounded-4 shadow-sm h-100">
                <i class="fa-solid fa-search fa-3x mb-3"></i>

                <h3 class="fw-bold">Lost Something?</h3>

                <p class="small opacity-75">
                    Submit a report so your neighbors can help you find it.
                </p>

                <a href="{{ route('user.report.lost') }}"
                   class="btn btn-light text-danger btn-round mt-3 fw-bold">
                    Post Lost Notice
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-5 bg-primary text-white rounded-4 shadow-sm h-100">
                <i class="fa-solid fa-hand-holding-heart fa-3x mb-3"></i>

                <h3 class="fw-bold">Found Something?</h3>

                <p class="small opacity-75">
                    Be a hero! Report found items to return them to their owners.
                </p>

                <a href="{{ route('user.report.found') }}"
                   class="btn btn-light text-primary btn-round mt-3 fw-bold">
                    Post Found Item
                </a>
            </div>
        </div>

    </div>

    {{-- Community notices --}}
    <h2 class="fw-bold mb-4">Community Notices</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($items->isNotEmpty())
        <div class="row">

            @foreach ($items as $item)
                @php
                    $isLost = $item->category === 'Lost';
                    $btnClass = $isLost ? 'danger' : 'primary';
                @endphp

                <div class="col-md-4 mb-4">
                    <div class="card notice-card h-100 shadow-sm border-0 rounded-4 overflow-hidden
                        {{ $item->status === 'Pending' ? 'pending-card' : '' }}">

                        @if (!empty($item->image))
                            <img
                                src="{{ asset('uploads/' . basename($item->image)) }}"
                                class="item-image"
                                alt="Item photo"
                            >
                        @else
                            <div class="bg-light text-center py-5 border-bottom">
                                <i class="fa-solid fa-image fa-3x text-muted opacity-25"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column p-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-{{ $btnClass }} px-3 py-2 shadow-sm">
                                    {{ strtoupper($item->category) }}
                                </span>

                                @if ($item->status === 'Pending')
                                    <span class="badge bg-light text-dark border shadow-sm">
                                        <i class="fa-solid fa-clock me-1 text-{{ $btnClass }}"></i>
                                        PENDING
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-bold text-dark mb-2">
                               {{ $item->item_name }}
                            </h4>

                            <p class="text-muted small mb-1">
                                <i class="fa-solid fa-location-dot me-2 text-{{ $btnClass }}"></i>
                                {{ $item->location }}
                            </p>

                            <p class="text-muted small mb-4">
                                <i class="fa-solid fa-calendar me-2 text-{{ $btnClass }}"></i>
                                {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}
                            </p>

                            <div class="mt-auto pt-3 border-top">

                                @if ($item->status === 'Pending')
                                    <div class="text-center">
                                        <p class="small fw-bold text-{{ $btnClass }} mb-2">
                                            Reviewing Submission...
                                        </p>

                                        <form action="{{ route('user.items.undo', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to remove this post?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-outline-{{ $btnClass }} btn-sm w-100 btn-round">
                                                <i class="fa-solid fa-rotate-left me-1"></i>
                                                Undo My Post
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('user.items.show', $item->id) }}"
                                       class="btn btn-{{ $btnClass }} w-100 btn-round shadow-sm">
                                        <i class="fa-solid fa-eye me-2"></i>
                                        View Details
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @else
        <div class="text-center py-5">
            <i class="fa-solid fa-box-open fa-3x text-secondary opacity-25 mb-3"></i>
            <p class="text-muted">
                There are currently no community notices.
            </p>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
