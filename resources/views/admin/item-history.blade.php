<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Item History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f6f8; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-4 py-3">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">🛡️ ADMIN MODERATION</span>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</nav>

<div class="container py-4">
    <h2 class="fw-bold mb-1">Item History</h2>
    <p class="text-muted mb-4">{{ $item->item_name }} — {{ $item->location }}</p>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Status Change</th>
                        <th>Changed By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $log)
                    <tr>
                        <td class="ps-4">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y h:i A') }}
                        </td>
                        <td>
                            @if (!is_null($log->old_status))
                                <span class="text-secondary">{{ $log->old_status }}</span>
                                <span class="mx-1">→</span>
                            @endif
                            <span class="fw-bold
                                @if($log->new_status === 'Pending') text-warning
                                @elseif($log->new_status === 'Approved') text-primary
                                @elseif($log->new_status === 'Resolved') text-success
                                @endif">
                                {{ $log->new_status }}
                            </span>
                        </td>
                        <td>{{ $log->changed_by }}</td>
                        <td class="text-muted">{{ $log->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>