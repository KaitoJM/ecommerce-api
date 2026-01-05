<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\CustomerRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected CustomerRepository $customerRepository
    ){}

    /**
     * Authenticate user.
     */
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->authenticate($request->only(["email", "role"]), $request->password);

            return response()->json([
                'user'  => $result->user,
                'token' => $result->token,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function loginCustomer(LoginRequest $request)
    {
        try {
            $result = $this->authService->authenticate([
                "email" => $request->email,
                "role" => "customer",
            ], $request->password, "web-app");

            return response()->json([
                'user'  => $this->customerRepository->getCustomerSingle(['user_id' => $result->user->id]),
                'token' => $result->token,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
