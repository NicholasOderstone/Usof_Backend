<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);
        $title = $request->input('title');
        $content = $request->input('content');
        $data = ['author' => auth()->user()->name,
                 'title' => $title,
                 'content' => $content];

        return Post::create($data);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post->author == auth()->user()->name || auth()->user()->role == 'admin') {
            $post->update($request->all());
            return $post;
        }
        else {
            return response()->json([
                'error' => 'User is not post author'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post->author == auth()->user()->name || auth()->user()->name == 'admin') {
            return Post::destroy($id);
        }
        else {
            return response()->json([
                'error' => 'User is not post author'
            ]);
        }
    }
}
