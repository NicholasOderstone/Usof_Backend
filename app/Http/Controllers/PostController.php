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
            'title' => 'required|string|max:512',
            'content' => 'required|string',
            'categories' => 'required|json'
        ]);
        $title = $request->input('title');
        $content = $request->input('content');
        $post_data = ['author' => auth()->user()->name,
                 'title' => $title,
                 'content' => $content];
        $new_post = Post::create($post_data);

        $categories = (json_decode($request->input('categories')))->id; 


        foreach ($categories as $category) {
            if(\App\Models\Category::find($category)) {
                CategoryController::create($category, $new_post->id);
            }
        }
        
        return $this->show($new_post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Post::find($id);
        $result->categories = CategoryController::getAllPostCategories($id);
        return $result;
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
        if ($post->author == auth()->user()->name || auth()->user()->is_admin == true) {
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
