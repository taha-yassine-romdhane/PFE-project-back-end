<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Models\PdfFile;

class FolderController extends Controller
{
    public function index()
    {
        try {
            $folders = Folder::with('children')->whereNull('parent_id')->get();

            // Transform the folders data into the expected format
            $formattedFolders = $folders->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'children' => $folder->children->map(function ($child) { 
                        return [
                            'id' => $child->id,
                            'name' => $child->name,

                        ];
                    }),
                ];
            });

            return response()->json($formattedFolders);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch folders: ' . $e->getMessage()], 500);
        }
    }
    public function indexx($id)
{
    try {
        $folder = Folder::with('children')->find($id);

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }

        // Transform the folder data into the expected format
        $formattedFolder = [
            'id' => $folder->id,
            'name' => $folder->name,
            'parent_id' => $folder->parent_id,
            'children' => $folder->children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'parent_id' => $child->parent_id,
                ];
            }),
        ];

        return response()->json($formattedFolder);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch folder: ' . $e->getMessage()], 500);
    }
}

public function store(Request $request)
{
    try {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
            'user_id' => 'nullable|exists:users,id', // Validate the user_id
        ]);

        // Log user information
        $user = $request->user();
        \Log::info('Creating folder', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        // Create folder with the user_id
        $folder = Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($folder, 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to create folder: ' . $e->getMessage()], 500);
    }
}


    public function update(Request $request, $id)
    {
        $folder = Folder::find($id);
    
        if (!$folder) {
            return response()->json(['message' => 'Folder not found.'], 404);
        }
    
        try {
            // Validation
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:folders,id',
            ]);
    
            // Update folder attributes
            $folder->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
    
            return response()->json(['message' => 'Folder updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while updating folder.'], 500);
        }
    }
    
    
    
    
    public function destroy($id)
    {
        try {
            $folder = Folder::find($id);
    
            if (!$folder) {
                return response()->json(['error' => 'Folder not found'], 404);
            }
    
            $folder->delete();
    
            return response()->json(['message' => 'Folder deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete folder: ' . $e->getMessage()], 500);
        }
    }
    public function getFolderFiles($id)
    {
        try {
            // Find the files with the given folder_id
            $files = PdfFile::where('folder_id', $id)->get();
    
            // Return the files as JSON response
            return response()->json(['files' => $files], 200);
        } catch (\Exception $e) {
            // Handle the exception if there's an error
            return response()->json(['error' => 'Failed to fetch files for the folder: ' . $e->getMessage()], 500);
        }
    }
    
    
}
