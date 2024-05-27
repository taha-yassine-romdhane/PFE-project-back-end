<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;


class PdfFileController extends Controller
{
    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'pdf_file' => 'required|array',
            'pdf_file.*' => 'required|mimes:pdf',
            'folder_id' => 'nullable|exists:folders,id',
        ]);
    
        $uploadedFiles = [];
        foreach ($request->file('pdf_file') as $file) {
            $originalFilename = $file->getClientOriginalName();
            $filePath = public_path('pdfs/' . $originalFilename);
    
            $file->move(public_path('pdfs'), $originalFilename);
    
            $pdfFile = new PdfFile();
            $pdfFile->filename = $originalFilename;
            $pdfFile->file_path = 'pdfs/' . $originalFilename;
            $pdfFile->folder_id = $request->input('folder_id');
            $pdfFile->save();
    
            $outputFolder = public_path('pdf_images/' . $pdfFile->id);
            if (!file_exists($outputFolder)) {
                mkdir($outputFolder, 0777, true);
            }
    
            $client = new Client();
            $response = $client->post('http://localhost:5000/convert', [
                'json' => [
                    'pdf_id' => $pdfFile->id,
                    'output_folder' => $outputFolder,
                ],
            ]);
    
            $conversionOutput = json_decode($response->getBody()->getContents(), true);
    
            $uploadedFiles[] = [
                'pdfFile' => $pdfFile,
                'filePath' => $pdfFile->file_path,
                'conversion_output' => $conversionOutput,
            ];
        }
    
        return response()->json(['message' => 'PDF files stored and converted successfully', 'files' => $uploadedFiles]);
    }
    public function index()
    {
        $documents = PdfFile::all(); // Fetch all documents from the pdf_files table
        return response()->json(['documents' => $documents]);
    } 
    public function show($filename)
    {
        $filePath = public_path('pdfs/' . $filename); // Use public_path for accessing files in public directory

        if (!file_exists($filePath)) {
            Log::error("File not found: {$filePath}");
            return response()->json(['message' => 'File not found'], 404);
        }

        // Specify CORS headers
        $corsHeaders = [
            'Access-Control-Allow-Origin' => env('FRONTEND_URL', 'http://localhost:3000'),
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',-
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
            'Content-Type' => 'application/pdf',
        ];

        // Log the headers
        foreach ($corsHeaders as $key => $value) {
            Log::info("Sending header {$key}: {$value}");
        }

        // Set headers using header() function
        foreach ($corsHeaders as $key => $value) {
            header("{$key}: {$value}");
        }

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
    public function getFilePathById($id)
    {
        $pdfFile = PdfFile::find($id);

        if (!$pdfFile) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }

        return response()->json(['filePath' => '/' . $pdfFile->file_path]);
    }
 



}

