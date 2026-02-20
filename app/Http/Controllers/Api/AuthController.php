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
                $photoUrl = $this->uploadToCloudinary($request->photo, 'profiles');
                if ($photoUrl) {
                    $user->photo = $photoUrl;
                    $photo = $photoUrl;
                }
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

    /**
     * Upload base64 image to Cloudinary and return the secure URL
     */
    private function uploadToCloudinary($base64Data, $folder)
    {
        try {
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey    = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');

            // Debug: log credential info (bukan secret-nya)
            \Log::error('[Cloudinary] Starting upload', [
                'folder'     => $folder,
                'cloud_name' => $cloudName,
                'has_key'    => !empty($apiKey),
                'has_secret' => !empty($apiSecret),
                'data_length'=> strlen($base64Data ?? ''),
            ]);

            $timestamp = time();
            $params    = "folder={$folder}&timestamp={$timestamp}{$apiSecret}";
            $signature = sha1($params);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'file'      => "data:image/jpeg;base64,{$base64Data}",
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'folder'    => $folder,
                'signature' => $signature,
            ]);

            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Debug: log raw response dari Cloudinary
            \Log::error('[Cloudinary] Response', [
                'curl_error' => $curlError,
                'response'   => $response,
            ]);

            $result = json_decode($response, true);
            $url = $result['secure_url'] ?? null;

            if (!$url) {
                \Log::error('[Cloudinary] No secure_url in response', ['result' => $result]);
            }

            return $url;
        } catch (\Exception $e) {
            \Log::error('[Cloudinary] Exception: ' . $e->getMessage());
            return null;
        }
    }

}
