<?php

namespace App\Http\Controllers;

use TCC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try
        {
            
            $user = User::create($request->except('password', 'password_confirmation') + ['password' => app('hash')->make($request->input('password'))]);

            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        try
        {
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $credentials = $request->only(['email', 'password']);
    
            if(!$token = Auth::attempt($credentials))
            {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken($token, Auth::user());
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }


}