<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // ✅ Added for consistent PH time handling

class UserItemController extends Controller
{
    public function show($id)
    {
        // Fetch item by ID
        $item = DB::table('items')->find($id);

        // If not found
        if (!$item) {
            abort(404, 'Notice not found.');
        }

        // Determine theme class
        $themeClass = ($item->category === 'Lost') ? 'danger' : 'primary';

        // Optional: Format created_at to PH time right here
        if (!empty($item->created_at)) {
            $item->formatted_date = Carbon::parse($item->created_at)
                ->setTimezone('Asia/Manila')
                ->format('M d, Y g:i A');
        }

        // Pass data to view
        return view('user.item-details', compact('item', 'themeClass'));
    }
}