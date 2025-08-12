<?php

namespace App\Repositories\Customer;
use App\Models\Customer;

class CustomerDbRepository implements ICustomerRepository
{

    public function getAllCustomers(): array
    {
        return Customer::all()->toArray();
    }

    public function findCustomerById(string $uuid): ?Customer
    {
        return Customer::where('uuid', $uuid)->first();
    }

    public function createCustomer(Customer $customer): void
    {
        $customer->save();
    }

    public function saveCustomer(Customer $customer): void
    {
        $customer->save();
    }

    public function deleteCustomer(string $uuid): bool
    {
        $deleted = Customer::where('uuid', $uuid)->delete();
        return $deleted > 0;
    }
}
