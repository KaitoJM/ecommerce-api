<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService {
    public function __construct(
        protected UserRepository $userRepository,
        protected CustomerRepository $customerRepository
    ){}

    public function authenticate($params, string $password) {
        $user = $this->userRepository->getUserSingle($params);

        if (!$user) {
            throw new Exception("Invalid credentials");
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception("Invalid credentials");
        }

        $token = $user->createToken('api-app');

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function authenticateCustomer($params, string $password) {
        $user = $this->userRepository->getUserSingle($params);

        if (!$user) {
            throw new Exception("Invalid credentials");
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception("Invalid credentials");
        }

        $token = $user->createToken('customer-app');
        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $user->id]);

        return [
            'user' => $customer,
            'token' => $token->plainTextToken
        ];
    }
}
