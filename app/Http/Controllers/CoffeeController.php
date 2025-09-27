<?php

namespace App\Http\Controllers;

use App\Models\Coffee;
use Illuminate\Http\Request;

class CoffeeController extends Controller
{
    // GET /api/coffees
    public function index()
    {
        return response()->json(Coffee::all(), 200);
    }

    // POST /api/coffees
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('coffees', 'public');
        }

        $coffee = Coffee::create($validated);
        return response()->json($coffee, 201);
    }

    // GET /api/coffees/{id}
    public function show($id)
    {
        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        return response()->json($coffee, 200);
    }

    // PUT /api/coffees/{id}
    public function update(Request $request, $id)
    {
        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('coffees', 'public');
        }

        $coffee->update($validated);
        return response()->json($coffee, 200);
    }

    // DELETE /api/coffees/{id}
    public function destroy($id)
    {
        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        $coffee->delete();
        return response()->json(['message' => 'Coffee deleted successfully'], 200);
    }
}