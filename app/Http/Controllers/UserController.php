<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Jobs\ProcessUserJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            if ($users->isEmpty()) {
                return (new MessageResource(null, false, 'Data not found'))->response()->setStatusCode(404);
            }
        } catch (\Exception $e) {
            return (new MessageResource(null, false, 'Failed to get users', $e->getMessage()))->response()->setStatusCode(500);
        }
        return (new MessageResource(UserResource::collection($users), true, 'User data found'))->response();
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return (new MessageResource(null, false, 'Data not found'))->response()->setStatusCode(404);
            }
        } catch (\Exception $e) {
            return (new MessageResource(null, false, 'Failed to get user', $e->getMessage()))->response()->setStatusCode(500);
        }
        return (new MessageResource(new UserResource($user), true, 'User data found'))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return (new MessageResource(null, false, 'Validation failed', $request->validator->messages()))->response()->setStatusCode(400);
        }

        try {
            $validated = $request->validated();
            $user = User::create($validated);
            ProcessUserJob::dispatch($user->id)->onQueue('user-created');
        } catch (\Exception $e) {
            return (new MessageResource(null, false, 'Failed to create user', $e->getMessage()))->response()->setStatusCode(500);
        }
        return (new MessageResource(new UserResource($user), true, 'User created successfully'))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id): JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return (new MessageResource(null, false, 'Validation failed', $request->validator->messages()))->response()->setStatusCode(400);
        }


        try {
            $user = User::find($id);
            if (!$user) {
                return (new MessageResource(null, false, 'Data not found'))->response()->setStatusCode(404);
            }
            $validated = $request->validated();
            $user->update($validated);
        } catch (\Exception $e) {
            return (new MessageResource(null, false, 'Failed to update user', $e->getMessage()))->response()->setStatusCode(500);
        }
        return (new MessageResource(new UserResource($user), true, 'User updated successfully'))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return (new MessageResource(null, false, 'Data not found'))->response()->setStatusCode(404);
            }
            $user->delete();
            ProcessUserJob::dispatch($user->id)->onQueue('user-deleted');
        } catch (\Exception $e) {
            return (new MessageResource(null, false, 'Failed to delete user', $e->getMessage()))->response()->setStatusCode(500);
        }
        return (new MessageResource(new UserResource($user), true, 'User deleted successfully'))->response();
    }
}
