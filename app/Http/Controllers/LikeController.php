<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function IndexLikePost($post_id)
    {
        return Like::where('entity', 'post')
                   ->where('entity_id', $post_id)
                   ->get();
    }

    public function IndexLikeComment($comment_id)
    {
        return Like::where('entity', 'comment')
                   ->where('entity_id', $comment_id)
                   ->get();
    }

    static public function PostRating($post_id) {
        return Like::where('entity', 'post')
                    ->where('entity_id', $post_id)
                    ->count();
    }

    static public function CommentRating($comment_id) {
        return Like::where('entity', 'comment')
                    ->where('entity_id', $comment_id)
                    ->count();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function StoreLikePost(Request $request, $post_id)
    {

        if (Post::find($post_id) == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such post. Post with id $post_id not found."
                ]
            ]); 
        }
        if ($like = Like::where('entity', 'post')
                        ->where('entity_id', $post_id)
                        ->where('user_id', auth()->user()->id)
                        ->first()) {
                            return response()->json([
                                "error" => [
                                    "message"  => "Like already exists"
                                ]]); 
                        }
        $data = [
            'user_id' => auth()->user()->id,
            'entity' => 'post',
            'entity_id' => $post_id
        ];

        return Like::create($data);
        
    }

    public function StoreLikeComment(Request $request, $comment_id)
    {

        if (Comment::find($comment_id) == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such comment. Comment with id $comment_id not found."
                ]
            ], 404); 
        }

        if ($like = Like::where('entity', 'comment')
                        ->where('entity_id', $comment_id)
                        ->where('user_id', auth()->user()->id)
                        ->first()) {
                            return response()->json([
                                "error" => [
                                    "message"  => "Like already exists"
                                ], 
                                "code" => 404]); 
                        }

        $data = [
            'user_id' => auth()->user()->id,
            'entity' => 'comment',
            'entity_id' => $comment_id
        ];

        return Like::create($data);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DestroyLikePost($post_id)
    {
        $like = Like::where('entity', 'post')
                    ->where('entity_id', $post_id)
                    ->where('user_id', auth()->user()->id)
                    ->first();
        return Like::destroy($like->id);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DestroyLikeComment($comment_id)
    {
        $like = Like::where('entity', 'comment')
                    ->where('entity_id', $comment_id)
                    ->where('user_id', auth()->user()->id)
                    ->first();
        return Like::destroy($like->id);
    }
}
