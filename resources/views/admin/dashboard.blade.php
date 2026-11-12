<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f6f8;
            color: #212529;
        }
        .admin-navbar {
            background: #1a1d20;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
        }
        .items-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        .table th {
            white-space: nowrap;
        }
        .action-form {
            display: inline-block;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark admin-navbar px-4 py-3">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">
            🛡️ ADMIN MODERATION
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-light">
                Logged in: {{ session('username', 'admin') }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to log out?');">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card stat-card p-4 bg-primary text-white shadow-sm">
                <h5><i class="fa-solid fa-boxes-stacked me-2"></i>Total Items</h5>
                <h2 class="fw-bold mb-0">{{ $total }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card p-4 bg-warning text-dark shadow-sm">
                <h5><i class="fa-solid fa-clock me-2"></i>Pending Items</h5>
                <h2 class="fw-bold mb-0">{{ $pending }}</h2>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Reported Items</h4>
        <span class="text-muted">{{ $items->count() }} shown</span>
    </div>

    <div class="card items-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Item</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="ps-4">
                                <strong>{{ $item->item_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $item->location ?? '' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($item->status === 'Approved')
                                    <span class="badge bg-primary">Approved</span>
                                @else
                                    <span class="badge bg-success">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    {{-- ✅ VIEW HISTORY BUTTON --}}
                                    <a href="{{ route('admin.item.history', $item->id) }}"
                                       class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-clock-rotate-left"></i> History
                                    </a>

                                    @if ($item->status === 'Pending')
                                        <form class="action-form" method="POST"
                                              action="{{ route('admin.items.status', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="Approved">
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    @elseif ($item->status === 'Approved')
                                        <form class="action-form" method="POST"
                                              action="{{ route('admin.items.status', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="Pending">
                                            <button class="btn btn-sm btn-outline-warning">
                                                <i class="fa-solid fa-rotate-left"></i> Undo
                                            </button>
                                        </form>
                                        <form class="action-form" method="POST"
                                              action="{{ route('admin.items.status', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="Resolved">
                                            <button class="btn btn-sm btn-primary">Resolve</button>
                                        </form>
                                    @endif

                                    <form class="action-form" method="POST"
                                          action="{{ route('admin.items.delete', $item->id) }}"
                                          onsubmit="return confirm('Delete this item permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No items found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>