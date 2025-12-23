<?php

use App\Models\Customer;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

describe('Get Customers', function() {
    it('returns a list of customers', function() {
        $user = User::factory()->create();

        $customerUser1 = User::factory()->create(['role' => 'customer']);
        Customer::factory(10)->create(['user_id' => $customerUser1->id]);

        $response = actingAs($user)->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it('returns a list of customers with search', function() {
        $user = User::factory()->create();

        $customerUser1 = User::factory()->create(['role' => 'customer']);
        $customerUser2 = User::factory()->create(['role' => 'customer']);

        Customer::factory()->create([
            'first_name' => 'Juan',
            'user_id' => $customerUser1->id
        ]);

        Customer::factory()->create([
            'first_name' => 'Jane',
            'user_id' => $customerUser2->id
        ]);

        $response = actingAs($user)->getJson('/api/customers?search=Juan');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'first_name' => 'Juan',
        ]);
    });
});

describe('Create Customer', function() {
    it ('creates a new user and customer if user is authenticated', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/customers', [
            'first_name' => 'John',
            'last_name' => 'doe',
            'email' => 'customer@test.com',
            'password' => '13123123',
            'gender' => 'male',
            'birthday' => '1993-10-04',
        ]);

        $response->assertStatus(201);
        assertDatabaseHas('users', [
            'name' => 'John doe',
            'email' => 'customer@test.com',
            'role' => 'customer'
        ]);
        $response->assertJsonFragment([
            'first_name' => 'John',
            'last_name' => 'doe',
        ]);
    });

    it ('returns a 422 error if the request has no email', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/customers', []);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'email' => ['The email field is required.'],
        ]);
    });

    it ('returns a 422 error if the request has no password', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->postJson('/api/customers', [
            'email' => 'test@test.com'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'password' => ['The password field is required.'],
        ]);
    });
});

describe('Get Customer', function() {
    it ('returns a customer if it exists', function() {
        $user = User::factory()->create();
        $customerUser1 = User::factory()->create(['role' => 'customer']);

        $customer = Customer::factory()->create([
            'first_name' => 'Test',
            'user_id' => $customerUser1->id
        ]);

        $response = actingAs($user)->getJson('/api/customers/' . $customer->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'first_name' => 'Test',
        ]);
    });

    it ('returns a 404 error if the user is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->getJson('/api/customers/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Customer not found',
        ]);
    });
});

describe('Update Customer', function() {
    it ('updates a customer if user is authenticated', function() {
        $user = User::factory()->create();
        $customerUser1 = User::factory()->create(['role' => 'customer']);

        $customer = Customer::factory()->create([
            'first_name' => 'Test',
            'user_id' => $customerUser1->id
        ]);

        $response = actingAs($user)->putJson('/api/customers/' . $customer->id, [
            'first_name' => 'Updated Customer',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'first_name' => 'Updated Customer',
        ]);
    });

    it ('returns a 404 error if the customer is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->putJson('/api/customers/999999', [
            'first_name' => 'Updated Customer',
        ]);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Customer not found',
        ]);
    });
});

describe('Delete Customer', function() {
    it ('deletes a customer if user is authenticated', function() {
        $user = User::factory()->create();
        $customerUser1 = User::factory()->create(['role' => 'customer']);

        $customer = Customer::factory()->create([
            'first_name' => 'Test',
            'user_id' => $customerUser1->id
        ]);

        $response = actingAs($user)->deleteJson('/api/customers/' . $customer->id);

        $response->assertStatus(204);
    });

    it ('returns a 404 error if the customer is not found', function() {
        $user = User::factory()->create();
        $response = actingAs($user)->deleteJson('/api/customers/999999');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Customer not found',
        ]);
    });
});
