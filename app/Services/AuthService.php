<?php

namespace App\Services;

use App\Dtos\AuthResultDto;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function __construct(
        protected UserRepository $userRepository,
    ){}

    /**
     * Authenticates a user
     *
     * @param  array{
     *     email: string
     *     role?: string|null
     * } $params
     * @param string $password
     * @param string $tokenKey
     *
     * @return \App\Dtos\AuthResultDto
     */
    public function authenticate($params, string $password, string $tokenKey = "backoffice-app"):AuthResultDto {
        $user = $this->userRepository->getUserSingle($params);

        if (!$user) {
            throw new AuthenticationException("Invalid credentials");
        }

        if (!Hash::check($password, $user->password)) {
            throw new AuthenticationException("Invalid credentials");
        }

        return new AuthResultDto(
            $user,
            $user->createToken($tokenKey)->plainTextToken
        );
    }
}
