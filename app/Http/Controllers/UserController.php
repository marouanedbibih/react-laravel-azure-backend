<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User */
        $user = User::query()->orderBy('id', 'desc')->paginate(10);
        return UserResource::collection($user);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        /** @var \App\Models\User $user */
        $user = User::create($data);
        return response([
            'message' => 'User add sucufuly',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
    
        // Check if the 'password' key exists in the data
        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        } else {
            // If 'password' key doesn't exist, remove it from the data
            unset($data['password']);
        }

    
        /** @var \App\Models\User $user */
        $user->update($data);
    
        return response([
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(['message' => 'User delete succufuly'],200);
    }
}
