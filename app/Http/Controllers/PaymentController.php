<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
            'payment_proof' => 'nullable|image|max:2048', // 2MB max
        ]);

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
        }

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'payment_method' => $request->payment_method,
            'payment_proof' => $path ? url('storage/'.$path) : null,
            'status' => 'pending'
        ]);
        
        // Optional: Update booking status to 'awaiting_approval' if you had that status
        
        return response()->json(['message' => 'Payment submitted', 'data' => $payment], 201);
    }

    public function index(Request $request)
    {
        // Admin only
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payments = Payment::with(['booking.user', 'booking.warehouse'])->latest()->get();

        return response()->json(['data' => $payments]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Admin only
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message' => 'Payment not found'], 404);

        $payment->update(['status' => $request->status]);

        // If approved, confirm the booking
        if ($request->status === 'approved') {
            $payment->booking->update(['status' => 'confirmed']);
        } else if ($request->status === 'rejected') {
            $payment->booking->update(['status' => 'pending']); // Back to pending or cancelled
        }

        return response()->json(['message' => 'Payment status updated', 'data' => $payment]);
    }
}
