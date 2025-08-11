<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct(private readonly CustomerService $customerService)
    {
    }

    public function getAll()
    {
        return response()->json($this->customerService->getAll(), 200);
    }

    public function getById(string $id)
    {
        $customer = $this->customerService->findById($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function create(CustomerCreateRequest $request)
    {
        $validated = $request->validated();
        $customer = Customer::make($validated);
        $createdCustomer = $this->customerService->create($customer);

        return response()->json($createdCustomer, 201);
    }

    public function update(CustomerUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $updatedCustomer = $this->customerService->update($id, $validated);

        return response()->json($updatedCustomer, 200);
    }


    public function delete(string $id)
    {
        $this->customerService->delete($id);
        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }
}
