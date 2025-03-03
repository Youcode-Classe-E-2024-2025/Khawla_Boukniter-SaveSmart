<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Raw request data:', $request->all());
        Log::info('Request headers:', $request->headers->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'type' => 'required|in:needs,wants,savings,income'
            ]);

            Log::info('Validation passed:', $validated);

            $category = Category::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'user_id' => Auth::id(),
                'family_id' => Auth::user()->family_id
            ]);

            Log::info('Category created:', $category->toArray());

            return response()->json($category, 201);
        } catch (\Exception $e) {
            Log::error('Detailed error:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'error' => 'Failed to create category',
                'message' => $e->getMessage()
            ], 422);
        }
    }


    public function getCategories()
    {
        $user = Auth::user();

        $categories = Category::query();

        if ($user->account_type === 'family') {
            $categories->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            });
        } else {
            $categories->where('user_id', $user->id);
        }

        return $categories->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
