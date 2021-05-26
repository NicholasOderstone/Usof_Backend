<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use App\QueryFilters\CategoryFilter;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryFilter $filter)
    {
        $categories = Category::filter($filter)->get();
        return CategoryResource::collection($categories);
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
            'title' => 'required|string|max:128|unique:categories',
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
    public function update(Request $request, $category_id)
    {
        if (auth()->user()->is_admin == true) {

            $category = Category::find($id);

            if ($category) {
                $input = $request->all();
                $input = $request->validate([
                    'title' => 'string|unique:categories'
                ]);
                $category->update($request->all());
                return $category;
            }
            else {
                return response()->json([
                    "error" => [
                        "message"  => "No such category. Category with id $id not found."
                    ],
                    400]);
            }
        }
        else {
            return response()->json([
                "error" => [
                    "message"  => "Access denied. You do not have permission for this action."
                ], 
                403]);
        }

        return response()->json([
            "error" => [
                "message"  => "Access denied. You do not have permission for this action."
            ]], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        if (auth()->user()->is_admin == true) {

            $category = Category::find($id);

            if ($category) {
                return Category::destroy($id);
            }
            else {
                return response()->json([
                    "error" => [
                        "message"  => "No such category. Category with id $id not found."
                    ],
                    400]);
            }
        }
        else {
            return response()->json([
                "error" => [
                    "message"  => "Access denied. You do not have permission for this action."
                ], 
                403]);
        }
        
        return response()->json([
            "error" => [
                "message"  => "Access denied. You do not have permission for this action."
            ]], 403);
    }
}
