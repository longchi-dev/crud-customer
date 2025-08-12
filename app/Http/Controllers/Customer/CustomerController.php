<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerDeleteRequest;
use App\Http\Requests\Customer\CustomerGetRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Services\Customer\CustomerLogService;
use App\Services\Customer\CustomerService;

class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly CustomerLogService $customerLogService
    ) {}

    public function getAll()
    {
        return $this->successResponse($this->customerService->getAll());
    }

    public function getById(CustomerGetRequest $request)
    {
        $uuid = $request->validated()['uuid'];
        $customer = $this->customerService->getById($uuid);
        return $this->successResponse($customer);
    }

    public function create(CustomerCreateRequest $request)
    {
        $validated = $request->validated();
        $createdCustomer = $this->customerService->create($validated['name'], $validated['totalAmount']);
        $this->customerLogService->log('create', $createdCustomer->toArray(), $createdCustomer->createdBy);
        return $this->successResponse($createdCustomer, 'Customer created successfully', 201);

    }

    /**
     * @throws CustomerNotFoundException
     */
    public function update(CustomerUpdateRequest $request)
    {
        $validated = $request->validated();
        $updatedCustomer = $this->customerService->update(
            $validated['uuid'],
            $validated['name'] ?? null,
            $validated['totalAmount'] ?? null
        );
        $this->customerLogService->log('update', $updatedCustomer->toArray(), $updatedCustomer->createdBy);
        return $this->successResponse($updatedCustomer, 'Customer updated successfully');
    }


    /**
     * @throws CustomerNotFoundException
     */
    public function delete(CustomerDeleteRequest $request)
    {
        $uuid = $request->validated()['uuid'];
        $this->customerService->delete($uuid);
        $this->customerLogService->log('delete', ['uuid' => $uuid], 'unknown');
        return $this->successResponse(null, 'Customer deleted successfully');
    }
}
