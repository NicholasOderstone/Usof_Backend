<?php

namespace App\Http\Controllers;
use App\Models\Like;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function StoreLikePost(Request $request, $post_id)
    {

        \App\Models\Post::findOrFail($post_id);

        $data = [
            'author' => auth()->user()->name,
            'entity' => 'post',
            'entity_id' => $post_id
        ];

        return Like::create($data);
        
    }

    public function StoreLikeComment(Request $request, $comment_id)
    {

        \App\Models\Comment::findOrFail($comment_id);

        $data = [
            'author' => auth()->user()->name,
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
                    ->where('author', auth()->user()->name)
                    ->get();
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
                    ->where('author', auth()->user()->name)
                    ->get();
        return Like::destroy($like->id);
    }
}
