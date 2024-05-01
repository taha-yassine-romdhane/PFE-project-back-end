<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Folder;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'Folder_id' => 'nullable|exists:categories,id'
        ]);

        // Log the incoming request data
        logger()->info('Request Data:', $request->all());

        $category = Category::create([
            'name' => $request->name,
            'Folder_id' => $request->parent_id
        ]);

        return response()->json(['success' => true, 'data' => $category], 201);
    }
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
    public function indexx($id)
    {
        $folder = Folder::findOrFail($id);
        $categories = $folder->categories()->get();

        return response()->json($categories);
    }
    public function destroy($id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            // Delete the category
            $category->delete();

            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category', 'error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->only('name'));
        return response()->json($category);
    }




}
