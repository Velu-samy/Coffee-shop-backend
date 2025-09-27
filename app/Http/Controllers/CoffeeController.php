<?php

namespace App\Http\Controllers;

use App\Models\Coffee;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class CoffeeController extends Controller
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

    // GET all coffees
    public function index()
    {
        return response()->json(Coffee::all(), 200);
    }

    // GET single coffee
    public function show($id)
    {
        $coffee = Coffee::find($id);
        if (!$coffee) return response()->json(['message' => 'Coffee not found'], 404);

        return response()->json($coffee, 200);
    }

    // CREATE coffee
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

        $coffee = Coffee::create($validated);
        return response()->json($coffee, 201);
    }

    // UPDATE coffee
    public function update(Request $request, $id)
    {
        $coffee = Coffee::find($id);
        if (!$coffee) return response()->json(['message' => 'Coffee not found'], 404);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'price'       => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $coffee->update($validated);
        return response()->json($coffee, 200);
    }

    // DELETE coffee
    public function destroy($id)
    {
        $coffee = Coffee::find($id);
        if (!$coffee) return response()->json(['message' => 'Coffee not found'], 404);

        // Optional: delete old image from Cloudinary if needed

        $coffee->delete();
        return response()->json(['message' => 'Coffee deleted successfully'], 200);
    }

    // --------------------------
    // Helper function for upload
    // --------------------------
    protected function uploadToCloudinary($file)
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'coffee-shop',
        ]);

        return $uploaded['secure_url'];
    }
}
