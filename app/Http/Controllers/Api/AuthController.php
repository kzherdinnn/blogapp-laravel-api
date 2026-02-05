<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){

        $creds = $request->only(['email','password']);

        if(!$token=auth()->attempt($creds)){
            
            return response()->json([
                'success' => false,
                'message' => 'invalid credintials'
            ]);
        }
        return response()->json([
            'success' =>true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }


    public function register(Request $request){

        $encryptedPass = Hash::make($request->password);

        $user = new User;

        try{
            $user->email = $request->email;
            $user->password = $encryptedPass;
            $user->save();
            return $this->login($request);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }
    }

    public function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout success'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ]);
        }
    }

    // this function saves user name,lastname and photo
    public function saveUserInfo(Request $request){
        try {
            // Validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'photo' => 'nullable|string'
            ]);

            $user = User::find(Auth::user()->id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $photo = '';
            
            //check if user provided photo
            if($request->photo && $request->photo != ''){
                // Create storage/profiles directory if it doesn't exist
                $profilesPath = storage_path('app/public/profiles');
                if (!file_exists($profilesPath)) {
                    mkdir($profilesPath, 0755, true);
                }

                // user time for photo name to prevent name duplication
                $photo = time().'.jpg';
                // decode photo string and save to storage/profiles
                file_put_contents($profilesPath.'/'.$photo, base64_decode($request->photo));
                $user->photo = $photo;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User info updated successfully',
                'photo' => $photo,
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error saving user info: '.$e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
