<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

class AuthController extends Controller
{
    
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'avatar' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Register Failed',
                    'errors' => ([
                        $validateUser->errors()
                    ])
                ], 401);
            }

            // upload file
            $image = $request->file('avatar');
            $image->storeAs('public/avatar', $image->hashName());

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                // 'avatar' => $request->file('avatar')
                'avatar' => 'http://localhost:8081/storage/avatar/'.$image->hashName()
            ]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Register Berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Register Failed',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }

 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'data' => [
                'id' => JWTAuth::user()->id,
                'email' => JWTAuth::user()->email,
                'name' => JWTAuth::user()->name,
                'avatar' => JWTAuth::user()->avatar,
                '_token' => $token
            ],
            'message' => 'Success Login'
        ]);
    }
    
    public function logoutUser()
    {
        // //valid credential
        // $validator = Validator::make($request->only('token'), [
        //     'token' => 'required'
        // ]);

        // //Send failed response if request is not valid
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->messages()], 200);
        // }
        

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
