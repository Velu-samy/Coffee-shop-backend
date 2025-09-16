<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use Illuminate\Http\Request;

class DrinksController extends Controller
{
    // Display a listing of drinks
    public function index()
    {
        return response()->json(Drink::all(), 200);
    }

    // Store a newly created drink
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

         if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('drinks', 'public');
        }
        $drink = Drink::create($validated);

        return response()->json($drink, 201);
    }

    // Display the specified drink
    public function show($id)
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return response()->json(['message' => 'Drink not found'], 404);
        }

        return response()->json($drink, 200);
    }

    // Update the specified drink
    public function update(Request $request, $id)
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return response()->json(['message' => 'Drink not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);
         
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink->update($validated);

        return response()->json($drink, 200);
    }

    // Remove the specified drink
    public function destroy($id)
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return response()->json(['message' => 'Drink not found'], 404);
        }

        $drink->delete();

        return response()->json(['message' => 'Drink deleted successfully'], 200);
    }
}