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
        return User::all();
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
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
        $user = User::find($id);
        if (auth()->user()->is_admin == true || auth()->user()->name == auth()->user()->name) {
            $user->update($request->all());
            return $user;
        }
        else { 
            return response("Access denied!", 401);
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
        $user = User::find($id);
        if (auth()->user()->is_admin == true || $user->name == auth()->user()->name) {
            return User::destroy($id);
        }
        else { 
            return response("Access denied!", 401);
        }
    }
}
