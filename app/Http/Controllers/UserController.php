<?php

namespace App\Http\Controllers;

use App\Http\Requests\user\CreateUserRequest;
use App\Http\Requests\user\GetUserRequest;
use App\Http\Requests\user\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetUserRequest $request)
    {
        $users = $this->userService->getUsers($request->query('search'));

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser(
            $request->only([
                'name',
                'email',
                'password',
                'role',
            ])
        );

        return response()->json(['data' => $user])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['data' => new UserResource($user)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = $this->userService->updateUser(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(null, 204);
    }
}
