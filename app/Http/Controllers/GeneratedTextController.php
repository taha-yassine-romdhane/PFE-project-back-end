<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pdffile;
use Illuminate\Support\Facades\Log;

class GeneratedTextController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'pdf_path' => 'required|string',
                'generated_texts' => 'required|array',
            ]);

            $pdffile = Pdffile::where('file_path', $data['pdf_path'])->first();

            if ($pdffile) {
                $pdffile->file_data = json_encode($data['generated_texts']);
                $pdffile->save();

                return response()->json(['message' => 'Generated texts stored successfully'], 200);
            }

            return response()->json(['message' => 'PDF file not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error storing generated texts: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
