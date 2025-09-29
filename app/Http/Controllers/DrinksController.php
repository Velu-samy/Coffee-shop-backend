<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class DrinksController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    // GET all drinks
    public function index()
    {
        return response()->json(Drink::all(), 200);
    }

    // GET single drink
    public function show($id)
    {
        $drink = Drink::find($id);
        if (!$drink) return response()->json(['message' => 'Drink not found'], 404);

        return response()->json($drink, 200);
    }

    // CREATE drink
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $drink = Drink::create($validated);
        return response()->json($drink, 201);
    }

    // UPDATE drink
    public function update(Request $request, $id)
    {
        $drink = Drink::find($id);
        if (!$drink) return response()->json(['message' => 'Drink not found'], 404);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'price'       => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $drink->update($validated);
        return response()->json($drink, 200);
    }

    // DELETE drink
    public function destroy($id)
    {
        $drink = Drink::find($id);
        if (!$drink) return response()->json(['message' => 'Drink not found'], 404);

        // Optional: delete old image from Cloudinary if needed

        $drink->delete();
        return response()->json(['message' => 'Drink deleted successfully'], 200);
    }

    // --------------------------
    // Helper function for upload
    // --------------------------
    protected function uploadToCloudinary($file)
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'drinks-menu',
        ]);

        return $uploaded['secure_url'];
    }
}