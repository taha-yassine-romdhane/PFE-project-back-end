<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function getImagesByPdfId($pdf_id)
    {
        $images = Image::where('pdf_id', $pdf_id)->get();
        return response()->json($images);
    }
    public function getAllImages()
    {
        $images = Image::all();
        return response()->json($images);
    }
    public function copyImage($folder, $filename)
    {
        $sourcePath = storage_path('app/public/pdf_images/' . $folder . '/' . $filename);
        $destinationPath = storage_path('app/public/temp_images/' . $filename);

        if (!File::exists($sourcePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        File::copy($sourcePath, $destinationPath);

        return response()->json(['path' => '/storage/temp_images/' . $filename]);
    }

    public function deleteImage($filename)
    {
        $path = storage_path('app/public/temp_images/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['message' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

}
