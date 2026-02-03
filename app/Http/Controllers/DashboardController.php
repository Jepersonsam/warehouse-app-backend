<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        // Simple authorization check
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalWarehouses = Warehouse::count();
        $totalBookings = Booking::count();
        
        // Revenue from approved bookings
        $revenue = Booking::where('status', 'approved')->sum('total_price');

        // Recent bookings (last 5)
        $recentBookings = Booking::with(['user', 'warehouse'])->latest()->take(5)->get();

        return response()->json([
            'total_warehouses' => $totalWarehouses,
            'total_bookings' => $totalBookings,
            'revenue' => $revenue,
            'recent_bookings' => $recentBookings
        ]);
    }
}
