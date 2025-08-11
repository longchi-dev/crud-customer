<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
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

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'total_amount' => 'required|numeric',
            'created_by' => 'required|string',
        ]);

        $customer = new Customer();
        $customer->name = $validated['name'];
        $customer->total_amount = (float)$validated['total_amount'];
        $customer->vip_level = 0;
        $customer->created_by = $validated['created_by'];

        $createdCustomer = $this->customerService->create($customer);

        return response()->json($createdCustomer, 201);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'total_amount' => 'sometimes|numeric',
        ]);

        $customer = $this->customerService->findById($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if (isset($validated['name'])) {
            $customer->name = $validated['name'];
        }

        if (isset($validated['total_amount'])) {
            $customer->total_amount = (float)$validated['total_amount'];
        }

        $updatedCustomer = $this->customerService->update($customer);

        return response()->json($updatedCustomer, 200);
    }

    public function delete(string $id)
    {
        $deletedCustomer = $this->customerService->delete($id);

        if (!$deletedCustomer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }
}
