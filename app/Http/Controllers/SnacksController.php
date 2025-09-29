<?php

namespace App\Http\Controllers;

use App\Models\Snack;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class SnacksController extends Controller
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

    // List all snacks
    public function index()
    {
        return response()->json(Snack::all(), 200);
    }

    // Show a specific snack
    public function show($id)
    {
        $snack = Snack::find($id);
        if (!$snack) return response()->json(['message' => 'Snack not found'], 404);

        return response()->json($snack, 200);
    }

    // Create a new snack
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

        $snack = Snack::create($validated);
        return response()->json($snack, 201);
    }

    // Update a snack
    public function update(Request $request, $id)
    {
        $snack = Snack::find($id);
        if (!$snack) return response()->json(['message' => 'Snack not found'], 404);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'price'       => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $snack->update($validated);
        return response()->json($snack, 200);
    }

    // Delete a snack
    public function destroy($id)
    {
        $snack = Snack::find($id);
        if (!$snack) return response()->json(['message' => 'Snack not found'], 404);

        // Optional: delete image from Cloudinary if needed

        $snack->delete();
        return response()->json(['message' => 'Snack deleted successfully'], 200);
    }

    // --------------------------
    // Helper function for upload
    // --------------------------
    protected function uploadToCloudinary($file)
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'snacks-menu',
        ]);

        return $uploaded['secure_url'];
    }
}