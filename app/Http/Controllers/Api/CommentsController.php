<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function create(Request $request){
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->id;
        $comment->comment = $request->comment;
        $comment->save();
        $comment->user;

        return response()->json([
            'success' => true,
            'comment'=>$comment,
            'message' => 'comment added'
        ]);
    }

    public function update(Request $request){
        $comment = Comment::find($request->id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ]);
        }

        // check if user is editing his own comment
        if($comment->user_id != Auth::user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $comment->comment = $request->comment;
        $comment->update();

        return response()->json([
            'success' => true,
            'message' => 'Comment edited successfully',
            'comment' => $comment
        ]);
    }

    public function delete(Request $request)
    {
        $comment = Comment::find($request->query('id')); // Ambil dari query parameter
    
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ]);
        }
    
        if ($comment->user_id != Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ]);
        }
    
        $comment->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
    

    public function comments(Request $request){
        $comments = Comment::where('post_id',$request->id)->get();
        //show user of each comment
        foreach($comments as $comment){
            $comment->user;
        }

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
