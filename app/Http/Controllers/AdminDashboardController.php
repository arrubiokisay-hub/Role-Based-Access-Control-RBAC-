<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private function requireAdmin(Request $request)
    {
        if (
            !$request->session()->has('username') ||
            $request->session()->get('role') !== 'admin'
        ) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($redirect = $this->requireAdmin($request)) {
            return $redirect;
        }

        $total = DB::table('items')->count();
        $pending = DB::table('items')
            ->where('status', 'Pending')
            ->count();

        $items = DB::table('items')
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Resolved')")
            ->orderByDesc('created_at')
            ->get();

        return view('admin.dashboard', compact(
            'total',
            'pending',
            'items'
        ));
    }

    public function updateStatus(Request $request, int $id)
    {
        if ($redirect = $this->requireAdmin($request)) {
            return $redirect;
        }

        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Approved,Resolved'],
        ]);

        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            return back()->withErrors([
                'item' => 'Item not found.',
            ]);
        }

        // Save OLD status before updating
        $oldStatus = $item->status;
        $newStatus = $validated['status'];

        // Allow only the transitions shown in the dashboard.
        $allowed = match ($item->status) {
            'Pending' => ['Approved'],
            'Approved' => ['Pending', 'Resolved'],
            default => [],
        };

        if (!in_array($newStatus, $allowed, true)) {
            return back()->withErrors([
                'status' => 'This status change is not allowed.',
            ]);
        }

        // Update the item
        DB::table('items')
            ->where('id', $id)
            ->update([
                'status' => $newStatus,
                'updated_at' => now(),
            ]);

        // ✅ LOG THE STATUS CHANGE
        DB::table('item_status_logs')->insert([
            'item_id'    => $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => session('username'),
            'remarks'    => "Status changed from {$oldStatus} to {$newStatus}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Item status updated successfully.');
    }

    // ✅ NEW: View History Page
    public function viewHistory(int $id)
    {
        if ($redirect = $this->requireAdmin(request())) {
            return $redirect;
        }

        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            return back()->withErrors(['item' => 'Item not found.']);
        }

        $history = DB::table('item_status_logs')
            ->where('item_id', $id)
            ->orderByDesc('created_at')
            ->get();

        // Add creation entry if no logs yet
        if ($history->isEmpty()) {
            $history = collect([(object)[
                'old_status' => null,
                'new_status' => $item->status,
                'changed_by' => 'System / User',
                'remarks'    => 'Item was reported / created',
                'created_at' => $item->created_at,
            ]]);
        }

        return view('admin.item-history', compact('item', 'history'));
    }

    public function destroy(Request $request, int $id)
    {
        if ($redirect = $this->requireAdmin($request)) {
            return $redirect;
        }

        $deleted = DB::table('items')->where('id', $id)->delete();

        if (!$deleted) {
            return back()->withErrors([
                'item' => 'Item not found.',
            ]);
        }

        return back()->with('success', 'Item deleted successfully.');
    }
}