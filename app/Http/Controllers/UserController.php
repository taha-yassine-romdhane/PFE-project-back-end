<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Models\User; // Assuming you have a Client model
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'results' => $users
        ], 200);

    }
    public function store(UserStoreRequest $request)
    {
        try {
            //create User
            User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=>$request->password
            ]);
            //Return Json Response
        return response()->json([
            'message'=> "User successfuly created!"
        ],200);
             //Return Json Response
        } catch (\Exception $e) {
            return response()->json([
                'message'=> "somthing went really wrong!"
            ],500);
        }
    }

    public function show($id)
    {
        $users = User::find($id);
        if (!$users){
            return response()->json([
                'message'=>'user not found.'
            ],404);
        }
        return response()->json([
            'users' => $users
        ],200);
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
   
}