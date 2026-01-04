<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\RegistrationRequest;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected UserRepository $userRepository
    ) {}

    public function index(RegistrationRequest $request) {
        $customer = DB::transaction(function () use ($request) {
            // create user
            $user = $this->userRepository->createUser([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'customer'
            ]);

            $params = $request->only([
                'first_name',
                'last_name',
            ]);

            $params['user_id'] = $user->id;

            // create customer
            return $this->customerRepository->createCustomer($params);
        });

        return response()->json(['data' => $customer])->setStatusCode(201);
    }
}
