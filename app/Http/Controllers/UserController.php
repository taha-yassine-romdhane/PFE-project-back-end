<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Folder;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'results' => $users
        ], 200);

    }


    public function show($id)
    {
        $users = User::find($id);
        if (!$users) {
            return response()->json([
                'message' => 'user not found.'
            ], 404);
        }
        return response()->json([
            'users' => $users
        ], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;
            
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
    public function update(UserUpdateRequest $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        try {
            // Update the user attributes with the request data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->telephone = $request->telephone;
            
            // Save the updated user
            $user->save();
            
            return response()->json(['message' => 'User updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while updating user.'], 500);
        }
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        


         $user->delete();
        
        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
    public function storeChildFolder(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'parent_id' => 'required|exists:folders,id',
                'name' => 'required|string|max:255',
            ]);
    
            // Find the parent folder
            $parentFolder = Folder::findOrFail($request->parent_id);
    
            // Create the child folder
            $childFolder = new Folder();
            $childFolder->name = $request->name;
            $childFolder->parent_id = $parentFolder->id;
            $childFolder->save();
    
            return response()->json($childFolder, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create child folder: ' . $e->getMessage()], 500);
        }
    }


}