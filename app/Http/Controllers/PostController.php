<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\QueryFilters\PostFilter;
use App\Http\Resources\PostResource;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostFilter $filter)
    {
        $posts = Post::filter($filter)->get();
        return PostResource::collection($posts);
        // $posts = Post::all();
        // $result = array();
        // foreach ($posts as $post) {
        //     $result[] = $this->show($post->id);
        // }
        // return $result;
    }

    static public function getAllUserPosts($user_id) {
        User::findOrFail($user_id);
        
        return Post::where('user_id', $user_id)->get();
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
        
        $post_data = ['user_id' => auth()->user()->id,
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

    static public function getPostRating($id) {
        return LikeController::PostRating($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post_info = Post::find($id);


        if ($post_info == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such post. Post with id $id not found."
                ]
            ], 404); 
        };

        return new PostResource($post_info);
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
        if ($post->user_id == auth()->user()->id || auth()->user()->is_admin == true) {
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
        if ($post->user_id == auth()->user()->id || auth()->user()->name == 'admin') {
            return Post::destroy($id);
        }
        else {
            return response()->json([
                'error' => 'User is not post author'
            ]);
        }
    }
}
