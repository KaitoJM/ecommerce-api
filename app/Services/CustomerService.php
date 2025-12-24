<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;

class CustomerService {
    public function getCustomers(?string $search = null, $filters = null, $pagination = null) {
        return Customer::with('user')
        ->when($search, function($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%");
            });
        })->when(isset($filters['gender']), function($query) use ($filters) {
            $query->where('gender', $filters['gender']);
        })->when(isset($filters['birthday']), function($query) use ($filters) {
            $query->where('birthday', $filters['birthday']);
        })->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     name: string
     *     email: string
     *     password: string
     * }  $params
     * @return \App\Models\Customer
     */
    public function createCustomer($params) {
        $createdCustomer = Customer::create([
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'middle_name' => $params['middle_name'] ?? '',
            'gender' => $params['gender'],
            'birthday' => $params['birthday'],
            'phone' => $params['phone'] ?? '',
            'user_id' => $params['user_id'],
        ]);

        return $createdCustomer;
    }

    /**
     * Get a customer by its ID.
     *
     * @param int $id The ID of the customer to get
     * @return \App\Models\Customer
     */
    public function getCustomerById(int $id) {
        return Customer::with('user')->findOrFail($id);
    }

    /**
     * Update a customer by its ID.
     *
     * @param int $id The ID of the customer to update
     * @param array $params The parameters to update the customer with
     * @return \App\Models\Customer
     */
    public function updateCustomer(int $id, array $params) {
        $customer = $this->getCustomerById($id);

        $customer->update($params);

        return $customer;
    }

    /**
     * Delete a customer by its ID.
     *
     * @param int $id The ID of the customer to delete
     * @return \App\Models\Customer
     */
    public function deleteCustomer(int $id) {
        $customer = $this->getCustomerById($id);

        $customer->delete();

        return $customer;
    }
}
