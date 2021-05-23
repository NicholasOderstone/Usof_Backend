<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $result = array();
        foreach ($users as $users) {
            $result[] = $this->show($users->id);
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->is_admin != true) {
            return response("Access denied!", 401);
        }
        $validated = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|confirmed',
            'email' => 'required|string|unique:users',
            'is_admin' => 'required|boolean'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        return User::create($validated);
    }


    private function getUserRating($id) {
        $user_posts = PostController::getAllUserPosts($id);
        $user_comments = CommentController::getAllUserComments($id);
        $user_rating = 0;

        foreach ($user_posts as $post) {
            $post_rating= LikeController::PostRating($post->id);

            $user_rating += $post_rating;
        }

        foreach ($user_comments as $comment) {
            $comment_rating= LikeController::CommentRating($comment->id);

            $user_rating += $comment_rating;
        }

        return $user_rating;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_info = User::find($id);


        if ($user_info == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such user. User with id $id not found."
                ]
            ], 404); 
        }

        $user_info->rating = $this->getUserRating($id);
        return $user_info;
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_info = User::find($id);
        if ($user_info == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such user. User with id ' .$id . ' not found."
                ]
            ], 404); 
        }
        if (auth()->user()->is_admin == true || auth()->user()->name == auth()->user()->name) {
            $user->update($request->all());
            return $user;
        }
        else { 
            return response()->json([
                "error" => [
                    "message"  => "Access denied. You do not have permission for this action."
                ]
            ], 403);
        }
        
    }

    public function uploadAvatar(Request $request) {
        if ($request->file('image')) {
            $user = User::find(auth()->user()->id);
            if ($user->image != 'avatars/default.png')
                \Illuminate\Support\Facades\Storage::delete('public/' . $user->image);
            $user->update([
                'image' => $image = $request->file('image')->storeAs('avatars', $user->id . '_' . $request->file('image')->getClientOriginalName(), 'public')
            ]);

            return response([
                "message" => "Your avatar was uploaded",
                "image" => $image
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_info = User::find($id);
        if ($user_info == null) {
            return response()->json([
                "error" => [
                    "message"  => "No such user. User with id ' .$id . ' not found."
                ]
            ], 404); 
        }
        if (auth()->user()->is_admin == true || $user->name == auth()->user()->name) {
            AuthController::logout();
            return User::destroy($id);
        }
        else { 
            return response()->json([
                "error" => [
                    "message"  => "Access denied. You do not have permission for this action."
                ]
            ], 403);
        }
        

    }
}
