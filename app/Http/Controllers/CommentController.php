<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        return Comment::where('post_id', $post_id)->orderBy('created_at')->get();
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
        
        Post::findOrFail($post_id);

        $data = [
            'author' => auth()->user()->name,
            'content' => $request->input('content'),
            'post_id' => $post_id,
        ];

        return Comment::create($data);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Comment::find($id);
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
                'error' => 'No such comment',
                'message' => 'Comment with id ' .$id . ' not found.'
            ]);
        }
        if (auth()->user()->is_admin != true && auth()->user()->name != $comment->author) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'You do not have permission for this action.'
            ]);
        }

        
        $comment->update($request->all());
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

        if (auth()->user()->is_admin != true && auth()->user()->name != $comment->author) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'You do not have permission for this action.'
            ]);
        }

        
        return Comment::destroy($comment_id);
    }
}
