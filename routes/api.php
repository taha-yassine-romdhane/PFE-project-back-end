<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PdfFileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\CategoryController;

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
// api.php
Route::middleware('auth:sanctum')->post('/addnew', [UserController::class, 'store']);

// Define routes that require authentication middleware inside a middleware group
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();

    });

});



require __DIR__ . '/auth.php';

// Route for login


Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('usersupdate/{id}', [UserController::class, 'update']);
Route::delete('usersdelete/{id}', [UserController::class, 'destroy']);
Route::post('/upload', [PdfFileController::class, 'upload']);
Route::get('/pdf_files', [PdfFileController::class, 'index']);
Route::delete('/pdf_files/{id}', [PdfFileController::class, 'delete']);

Route::get('/pdfs/{filename}', [PdfFileController::class, 'show'])->name('pdf.show');



// Route for fetching all folders
Route::get('/folders', [FolderController::class, 'index']);

// Route for fetching a specific folder
Route::get('/folders/{id}', [FolderController::class, 'indexx']);

// Route for creating a new folder
Route::post('/folders', [FolderController::class, 'store']);

// Route for updating a folder
Route::put('/folders/{id}', [FolderController::class, 'update']);

// Route for deleting a folder
Route::delete('/folders/{id}', [FolderController::class, 'destroy']);

Route::post('/api/folders/{id}', [FolderController::class, 'storeChildFolder']);
// Route for fetching files associated with a specific folder
Route::get('/folders/{id}/files', [FolderController::class, 'getFolderFiles']);

Route::post('/categories', [CategoryController::class, 'addCategory']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/folders/{id}/categories', [CategoryController::class, 'indexx']);
Route::delete('/categories/{id}',[ CategoryController::class, 'destroy']);
Route::put('/categories/{id}', [ CategoryController::class, 'update']);

