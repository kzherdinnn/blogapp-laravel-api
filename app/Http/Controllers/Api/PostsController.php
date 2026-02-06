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
            // Choose a unique name for the photo
            $photo = time() . '.jpg';
            
            // Store photo in 'public/posts' folder
            Storage::disk('public')->put('posts/' . $photo, base64_decode($request->photo));
            
            // Set the photo filename in the post
            $post->photo = $photo;
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
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

