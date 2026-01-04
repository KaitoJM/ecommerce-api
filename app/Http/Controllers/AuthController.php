<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService){}

    /**
     * Authenticate user.
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->authenticate($request->only(["email", "role"]), $request->password);
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }

    public function loginCustomer(LoginRequest $request)
    {
        try {
            $data = $this->authService->authenticateCustomer([
                "email" => $request->email,
                "role" => "customer"
            ], $request->password);
            return response()->json($data);
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
