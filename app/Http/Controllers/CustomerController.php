<?php

namespace App\Http\Controllers;

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerDeleteRequest;
use App\Http\Requests\Customer\CustomerGetRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Services\Customer\CustomerService;

class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customerService)
    {
    }

    public function getAll()
    {
        return response()->json($this->customerService->getAll(), 200);
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function getById(CustomerGetRequest $request)
    {
        // Sửa throw Exception
        $id = $request->validated()['id'];
        $customer = $this->customerService->getById($id);
        return response()->json($customer, 200);
    }

    public function create(CustomerCreateRequest $request)
    {
        $validated = $request->validated();
        $createdCustomer = $this->customerService->create($validated);
        return response()->json($createdCustomer, 201);
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function update(CustomerUpdateRequest $request)
    {
        $validated = $request->validated();
        $updatedCustomer = $this->customerService->update($validated['id'], $validated);
        return response()->json($updatedCustomer, 200);
    }


    /**
     * @throws CustomerNotFoundException
     */
    public function delete(CustomerDeleteRequest $request)
    {
        $validated = $request->validated();
        $this->customerService->delete($validated['id']);
        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }
}
