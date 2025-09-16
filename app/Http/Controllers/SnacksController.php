<?php

namespace App\Http\Controllers;

use App\Models\Snack;
use Illuminate\Http\Request;

class SnacksController extends Controller
{
    // List all snacks
    public function index()
    {
        return response()->json(Snack::all(), 200);
    }

    // Create a new snack
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

         if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('snacks', 'public');
        }

        $snack = Snack::create($validated);

        return response()->json($snack, 201);
    }

    // Show a specific snack
    public function show($id)
    {
        $snack = Snack::find($id);

        if (!$snack) {
            return response()->json(['message' => 'Snack not found'], 404);
        }

        return response()->json($snack, 200);
    }

    // Update a snack
    public function update(Request $request, $id)
    {
        $snack = Snack::find($id);

        if (!$snack) {
            return response()->json(['message' => 'Snack not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);

        $snack->update($validated);

        return response()->json($snack, 200);
    }

    // Delete a snack
    public function destroy($id)
    {
        $snack = Snack::find($id);

        if (!$snack) {
            return response()->json(['message' => 'Snack not found'], 404);
        }

        $snack->delete();

        return response()->json(['message' => 'Snack deleted successfully'], 200);
    }
}