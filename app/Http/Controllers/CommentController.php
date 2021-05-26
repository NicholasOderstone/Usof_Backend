<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $comments = Comment::where('post_id', $post_id)->orderBy('created_at')->get();

        $result = array();
        foreach ($comments as $comment) {
            $result[] = $this->show($comment->id);
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {

        $required = $request->validate([
            'content' => 'required|string|max:512',
        ]);
        
        if (Post::find($post_id) == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such post. Post with id $post_id not found."
                ]
            ], 404); 
        }

        $data = [
            'user_id' => auth()->user()->id,
            'content' => $request->input('content'),
            'post_id' => $post_id,
        ];

        return Comment::create($data);
        
    }
    static public function getAllUserComments($user_id) {
        if (User::find($user_id) == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such user. Post with id $user_id not found."
                ]
            ], 404); 
        }
        
        return Comment::where('user_id', $user_id)->get();
    }

    private function getCommentRating($id) {
        return LikeController::CommentRating($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment_info = Comment::find($id);


        if ($comment_info == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such comment. Comment with id $id not found."
                ]
            ], 404); 
        }

        $comment_info->rating = $this->getCommentRating($id);
        return $comment_info;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        if (!$comment) {
            return response()->json([
                "error" => [
                    "message"  => "No such comment. Comment with id ' .$id . ' not found."
                ],
                400
            ]);
        }
        if (auth()->user()->is_admin != true && auth()->user()->id != $comment->user_id) {
            return response()->json([
                "error" => [
                    "message"  => "Access denied. You do not have permission for this action."
                ], 
                403]);
        }
        $input = $request->all();
        
        $comment->fill($input)->save();
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment_id)
    {
        $comment = Comment::find($comment_id);
        
        if (!$comment) {
            return response()->json([
                'error' => 'No such comment',
                'message' => 'Comment with id ' .$id . ' not found.'
            ]);
        }

        if (auth()->user()->is_admin != true && auth()->user()->id != $comment->user_id) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'You do not have permission for this action.'
            ]);
        }

        
        return Comment::destroy($comment_id);
    }
}
