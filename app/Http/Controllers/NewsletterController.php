<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    // 📨 Store a new subscriber
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mail' => 'required|email|unique:newsletters,mail',
        ]);

        Newsletter::create($validated);

        return response()->json(['message' => 'Subscriber saved successfully']);
    }

    // 📋 Get all subscribers
    public function index()
    {
        return response()->json(Newsletter::all());
    }
}