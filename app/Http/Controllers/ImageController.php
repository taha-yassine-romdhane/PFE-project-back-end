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
}
