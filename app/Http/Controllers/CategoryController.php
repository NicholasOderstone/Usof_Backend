<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    public function getAllPosts($category_id) {
        $post_ids = PostCategory::where('category_id', $category_id)->get();
        $result = [];
        foreach ($post_ids as $post) {
            $result[] = \App\Models\Post::find($post->post_id);
        }
        return $result;
    }

    static public function getAllPostCategories($post_id) {
        $post_categories_raw = \App\Models\PostCategory::select('category_id as id')->where('post_id', $post_id)->get();
        $post_categories = [];
        for ($i=0; $i < count($post_categories_raw); $i++) {
            $post_categories[$i] = $post_categories_raw[$i]->id;
        }
        return $post_categories;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $required = $request->validate([
            'title' => 'required|string|max:128',
        ]);

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description', 'No description')
        ];

        return Category::create($data);
        
    }

    static public function create($category_id, $post_id) {
        $data = [
            'category_id' => $category_id,
            'post_id' => $post_id
        ];

        return PostCategory::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
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
        if (auth()->user()->is_admin != true) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'You do not have permission for this action.'
            ]);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'error' => 'No such category',
                'message' => 'Category with id ' .$id . ' not found.'
            ]);
        }
        
        $category->update($request->all());
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->is_admin != true) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'You do not have permission for this action.'
            ]);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'error' => 'No such category',
                'message' => 'Category with id ' .$id . ' not found.'
            ]);
        }
        return Category::destroy($id);
    }
}
