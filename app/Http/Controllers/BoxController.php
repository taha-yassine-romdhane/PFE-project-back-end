<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\Category;

class BoxController extends Controller
{
    public function index()
    {
        // Fetch all boxes
        $boxes = Box::all();
        return response()->json($boxes);
    }

    public function getBoxesByCategory($categoryId)
    {
        \Log::info('Fetching boxes for category ID: ' . $categoryId);
        try {
            // Fetch boxes based on category ID
            $boxes = Box::where('category_id', $categoryId)->get();
            \Log::info('Fetched boxes: ' . $boxes->toJson());
            return response()->json($boxes);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch boxes: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }
    
    

    public function getCategories()
    {
        try {
            // Fetch all categories
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }

    // Add other necessary methods here, like store, update, destroy, etc.
}
