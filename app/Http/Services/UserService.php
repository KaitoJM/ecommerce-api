<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function getUsers(?string $search = null, $filters = null, $pagination = null) {
        return User::when($search, function($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })->when($filters['role'], function($query) use ($filters) {
            $query->where('role', $filters['role']);
        })->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     name: string
     *     email: string
     *     password: string
     *     role: string
     * }  $params
     * @return \App\Models\User
     */
    public function createUser($params) {
        $createdUser = User::create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
            'role' => $params['role'],
        ]);

        return $createdUser;
    }

    /**
     * Get a user by its ID.
     *
     * @param int $id The ID of the user to get
     * @return \App\Models\User
     */
    public function getUserById(int $id) {
        return User::findOrFail($id);
    }

    /**
     * Update a user by its ID.
     *
     * @param int $id The ID of the user to update
     * @param array $params The parameters to update the user with
     * @return \App\Models\User
     */
    public function updateUser(int $id, array $params) {
        $user = $this->getUserById($id);

        $user->update($params);

        return $user;
    }

    /**
     * Delete a user by its ID.
     *
     * @param int $id The ID of the user to delete
     * @return \App\Models\User
     */
    public function deleteUser(int $id) {
        $user = $this->getUserById($id);

        $user->delete();

        return $user;
    }
}
