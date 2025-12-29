<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\customer\CreateCustomerRequest;
use App\Http\Requests\Admin\customer\GetCustomerRequest;
use App\Http\Requests\Admin\customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    protected CustomerRepository $customerRepository;
    protected UserRepository $userRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        UserRepository $userRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetCustomerRequest $request)
    {
        $customers = $this->customerRepository->getCustomers($request->query('search'));

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
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
                'middle_name',
                'gender',
                'birthday',
            ]);

            $params['user_id'] = $user->id;

            // create customer
            return $this->customerRepository->createCustomer($params);
        });

        return response()->json(['data' => $customer])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = $this->customerRepository->getCustomerById($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json(['data' => new CustomerResource($customer)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        try {
            $customer = $this->customerRepository->updateCustomer(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->customerRepository->deleteCustomer($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json(null, 204);
    }
}
