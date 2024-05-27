<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PdfFileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GeneratedTextController;
use App\Http\Controllers\BoxController;

use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::options('/{any}', function () {
    return response()->json([], 200)
        ->header('Access-Control-Allow-Origin', env('FRONTEND_URL', 'http://localhost:3000'))
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->header('Access-Control-Allow-Credentials', 'true');
})->where('any', '.*');

Route::get('/pdfs/{filename}', [PdfFileController::class, 'show'])->name('pdf.show');

// Define routes that require authentication middleware inside a middleware group
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/folders', [FolderController::class, 'store']);
    Route::post('/addnew', [UserController::class, 'store']);
});





// User routes
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('usersupdate/{id}', [UserController::class, 'update']);
Route::delete('usersdelete/{id}', [UserController::class, 'destroy']);
Route::post('/upload', [PdfFileController::class, 'upload']);
Route::get('/pdf_files', [PdfFileController::class, 'index']);
Route::delete('/pdf_files/{id}', [PdfFileController::class, 'delete']);
Route::get('/pdf_files/{id}/path', [PdfFileController::class, 'getFilePathById']);

// Folder routes
Route::get('/folders', [FolderController::class, 'index']);
Route::get('/folders/{id}', [FolderController::class, 'indexx']);

Route::put('/folders/{id}', [FolderController::class, 'update']);
Route::delete('/folders/{id}', [FolderController::class, 'destroy']);
Route::post('/api/folders/{id}', [FolderController::class, 'storeChildFolder']);
Route::get('/folders/{id}/files', [FolderController::class, 'getFolderFiles']);

// Category routes
Route::post('/categories', [CategoryController::class, 'addCategory']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/folders/{id}/categories', [CategoryController::class, 'indexx']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);

Route::post('/save-image-and-category', [CategoryController::class, 'saveImageAndCategory']);
Route::get('/api/test-modal-data', [BoxController::class, 'getTestModalData']);
Route::get('/boxes/{categoryId}', [BoxController::class, 'getBoxesByCategory']);
Route::get('/categories', [BoxController::class, 'getCategories']);

Route::get('/folders', [FolderController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/boxes', [BoxController::class, 'index']);
Route::delete('/boxes/{id}', [BoxController::class, 'destroy']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);




Route::get('/images/{pdf_id}', [ImageController::class, 'getImagesByPdfId']);

Route::get('/images', [ImageController::class, 'getAllImages']);



// routes/api.php



Route::post('/store-generated-texts', [GeneratedTextController::class, 'store']);

require __DIR__ . '/auth.php';