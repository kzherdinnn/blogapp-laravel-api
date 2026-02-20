<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function create(Request $request)
    {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;

        // Check if post has a photo
        if($request->photo != '') {
            $photoUrl = $this->uploadToCloudinary($request->photo, 'posts');
            $post->photo = $photoUrl ?? '';
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
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

            \Log::error('[Cloudinary Posts] Starting upload', [
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

            \Log::error('[Cloudinary Posts] Response', [
                'curl_error' => $curlError,
                'response'   => $response,
            ]);

            $result = json_decode($response, true);
            $url = $result['secure_url'] ?? null;

            if (!$url) {
                \Log::error('[Cloudinary Posts] No secure_url', ['result' => $result]);
            }

            return $url;
        } catch (\Exception $e) {
            \Log::error('[Cloudinary Posts] Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'desc' => 'required|string|max:255',
        ]);
    
        $post = Post::find($id);
    
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }
    
        if (auth()->user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }
    
        $post->desc = $request->desc;
        $post->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);
    }
    

    public function delete($id)  // Ubah parameter untuk menerima ID dari URL
    {
        $post = Post::find($id);
    
        // Tambahkan pengecekan apakah post ditemukan
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
    
        // Check if user is deleting his own post
        if (Auth::user()->id != $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access'
            ], 403);  // Tambahkan status code 403 Forbidden
        }
    
        // Check if post has a photo to delete
        if ($post->photo != '') {
            Storage::disk('public')->delete('posts/' . $post->photo);
        }
    
        $post->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }

    public function posts()
    {
        $posts = Post::orderBy('id', 'desc')->get();

        foreach ($posts as $post) {
            // Get the user of the post
            $post->user;

            // Comments count
            $post['commentsCount'] = count($post->comments);

            // Likes count
            $post['likesCount'] = count($post->likes);

            // Check if users liked their own post
            $post['selfLike'] = false;
            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'user' => $user
        ]);
    }
}

