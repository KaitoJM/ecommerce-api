<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService {
    /**
     * @var \App\Models\User
     * Holds the authenticated user data
     */
    public $user;
    /**
     * @var string
     * The generated personal access token of the authenticated user
     */
    public $token;

    public function __construct(
        protected UserRepository $userRepository,
        protected CustomerRepository $customerRepository
    ){}

    /**
     * Authenticates a user and store the authentication data on public variables
     *
     * @param  array{
     *     email: string
     *     role?: string
     * } $params
     * @param string $password
     * @param string $tokenKey
     *
     */
    public function authenticate($params, string $password, string $tokenKey = "backoffice-app") {
        $user = $this->userRepository->getUserSingle($params);

        if (!$user) {
            throw new Exception("Invalid credentials");
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception("Invalid credentials");
        }

        $token = $user->createToken($tokenKey);

        $this->user = $user;
        $this->token = $token->plainTextToken;
    }

    /**
     * Retrieves the customer data using the stored authenticated user data
     *
     * @return \App\Models\Customer
     */
    public function getCustomer() {
        if (!$this->user) {
            throw new Exception("No athenticated user.");
        }

        $customer = $this->customerRepository->getCustomerSingle(['user_id' => $this->user->id]);
        return $customer;
    }
}
