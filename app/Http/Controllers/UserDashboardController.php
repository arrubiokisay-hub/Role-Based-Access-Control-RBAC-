<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    // User Dashboard
    public function index(Request $request)
    {
        if (
            !$request->session()->has('username') ||
            $request->session()->get('role') !== 'user'
        ) {
            return redirect()->route('user.login');
        }

        $items = DB::table('items')
            ->whereIn('status', ['Approved', 'Pending'])
            ->orderByDesc('created_at')
            ->get();

        return view('user.dashboard', compact('items'));
    }

    // Report Lost Page
    public function reportLost()
    {
        if (!session('username') || session('role') !== 'user') {
            return redirect()->route('user.login');
        }

        return view('user.report-lost');
    }

    // Submit Lost Report
    public function submitReportLost(Request $request)
    {
        if (!session('username') || session('role') !== 'user') {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'item_name'     => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'date_happened' => 'required|date',
            'description'   => 'nullable|string',
            'contact'       => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'item_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('item_image')) {
            $image = $request->file('item_image');
            $filename = time() . '_' . uniqid() . '.' .
                $image->getClientOriginalExtension();

            $image->move(public_path('uploads'), $filename);
            $imagePath = 'uploads/' . $filename;
        }

        $now = Carbon::now('Asia/Manila');

        $itemId = DB::table('items')->insertGetId([
            'item_name'     => $validated['item_name'],
            'location'      => $validated['location'],
            'date_happened' => $validated['date_happened'],
            'description'   => $validated['description'] ?? null,
            'contact_info'  => $validated['contact'],
            'category'      => 'Lost',
            'status'        => 'Pending',
            'user_id'       => session('user_id'),
            'image'         => $imagePath,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // Record initial status in the history table.
        DB::table('item_status_logs')->insert([
    'item_id'     => $itemId,
    'old_status'  => null,  // First time = no previous status
    'new_status'  => 'Pending',
    'changed_by'  => session('username') ?? 'System / User',
    'remarks'     => 'Item was reported / created',
    'created_at'  => $now,
]);

        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                'Your lost notice has been submitted and is pending approval.'
            );
    }

    // Report Found Page
    public function reportFound()
    {
        if (!session('username') || session('role') !== 'user') {
            return redirect()->route('user.login');
        }

        return view('user.report-found');
    }

    // Submit Found Report
    public function submitReportFound(Request $request)
    {
        if (!session('username') || session('role') !== 'user') {
            return redirect()->route('user.login');
        }

        $validated = $request->validate([
            'item_name'     => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'date_happened' => 'required|date',
            'description'   => 'nullable|string',
            'contact'       => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'item_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('item_image')) {
            $image = $request->file('item_image');
            $filename = time() . '_' . uniqid() . '.' .
                $image->getClientOriginalExtension();

            $image->move(public_path('uploads'), $filename);
            $imagePath = 'uploads/' . $filename;
        }

        $now = Carbon::now('Asia/Manila');

        $itemId = DB::table('items')->insertGetId([
            'item_name'     => $validated['item_name'],
            'location'      => $validated['location'],
            'date_happened' => $validated['date_happened'],
            'description'   => $validated['description'] ?? null,
            'contact_info'  => $validated['contact'],
            'category'      => 'Found',
            'status'        => 'Pending',
            'user_id'       => session('user_id'),
            'image'         => $imagePath,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // Record initial status in the history table.
        DB::table('item_status_logs')->insert([
            'item_id'    => $itemId,
            'old_status' => null,
            'new_status' => 'Pending',
            'changed_by' => session('username') ?? 'System / User',
            'remarks'    => 'Item was reported / created',
            'created_at' => $now,
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                'Your found notice has been submitted and is pending approval.'
            );
    }

    // View Single Item
    public function showItem($id)
    {
        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            abort(404, 'Item not found.');
        }

        $themeClass = ($item->category === 'Lost') ? 'danger' : 'primary';

        return view('user.item-details', compact('item', 'themeClass'));
    }

    // Delete Post
    public function undoPost(Request $request, $id)
    {
        if (!session('username') || session('role') !== 'user') {
            return redirect()->route('user.login');
        }

        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            abort(404, 'Item not found.');
        }

        // Only allow the owner to remove their own post.
        if (
            !empty($item->user_id) &&
            (string) $item->user_id !== (string) session('user_id')
        ) {
            abort(403, 'You cannot remove another user\'s post.');
        }

        DB::table('items')->where('id', $id)->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Your post has been removed.');
    }
}