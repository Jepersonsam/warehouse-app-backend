<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // If admin, return all bookings
        if ($request->user()->role === 'admin') {
            $bookings = Booking::with(['user', 'warehouse'])->latest()->get();
        } else {
            // Get current user's bookings with warehouse details
            $bookings = $request->user()->bookings()->with('warehouse')->latest()->get();
        }

        return response()->json(['data' => $bookings]);
    }

    public function show(Request $request, $id)
    {
        // Fetch booking with warehouse details
        $booking = Booking::with('warehouse')->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Authorization: Ensure user owns the booking or is admin
        if ($request->user()->role !== 'admin' && $booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_price' => 'required|numeric',
        ]);

        $booking = $request->user()->bookings()->create([
            'warehouse_id' => $request->warehouse_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'id' => $booking->id,
            'data' => $booking
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,cancelled',
        ]);

        $booking = Booking::find($id);
        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        $booking->update(['status' => $request->status]);

        return response()->json(['message' => 'Booking status updated', 'data' => $booking]);
    }
}
