<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfFile;
use Illuminate\Support\Facades\Storage;

class PdfFileController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request for each file
        $validatedData = $request->validate([
            'pdf_file' => 'required|array',
            'pdf_file.*' => 'required|mimes:pdf', // Adjust max file size and allowed file types as per your requirements
            'folder_id' => 'nullable|exists:folders,id', // Validate the folder_id if provided
        ]);

        // Store the uploaded files
        $uploadedFiles = [];
        foreach ($request->file('pdf_file') as $file) {
            // Generate a unique ID for the filename
            $newId = PdfFile::latest()->first()->id ?? 0;

            // Generate the new ID
            $newId = $newId + 1;

            $originalFilename = $file->getClientOriginalName();

            // Construct the new filename with the unique ID
            $newFilename =  $originalFilename;

            // Store the file in the database
            $pdfFile = new PdfFile();
            $pdfFile->filename = $newFilename;
            $pdfFile->file_path = $file->storeAs('pdfs', $newFilename); // Set the file path if needed
            $pdfFile->folder_id = $request->input('folder_id'); // Assign the folder_id
            $pdfFile->save();

            // Get the full file path
            $fullFilePath = storage_path('app/' . $pdfFile->file_path);

            $uploadedFiles[] = ['pdfFile' => $pdfFile, 'fullFilePath' => $fullFilePath];
        }

        return response()->json(['message' => 'PDF files stored successfully', 'files' => $uploadedFiles]);
    }

    public function index()
    {
        $documents = PdfFile::all(); // Fetch all documents from the pdf_files table
        return response()->json(['documents' => $documents]);
    }
    public function show($filename)
    {
        $filePath = storage_path('app/pdfs/' . $filename);
        return response()->file($filePath);
    }
    public function delete($id)
    {
        $pdfFile = PdfFile::find($id);

        if (!$pdfFile) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }

        // Delete the file from storage
        Storage::delete($pdfFile->file_path);

        // Delete the record from the database
        $pdfFile->delete();

        return response()->json(['message' => 'PDF file deleted successfully']);
    }
}

