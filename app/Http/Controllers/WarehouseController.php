<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        return response()->json(['data' => $query->get()]);
    }

    public function show($id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }

        return response()->json(['data' => $warehouse]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'price_per_month' => 'required|numeric',
            'size' => 'required|integer',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $warehouse = Warehouse::create($request->all());

        return response()->json(['message' => 'Warehouse created', 'data' => $warehouse], 201);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) return response()->json(['message' => 'Warehouse not found'], 404);

        $warehouse->update($request->all());

        return response()->json(['message' => 'Warehouse updated', 'data' => $warehouse]);
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) return response()->json(['message' => 'Warehouse not found'], 404);

        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted']);
    }
}
