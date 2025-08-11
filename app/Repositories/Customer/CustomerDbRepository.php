<?php

namespace App\Repositories\Customer;
use App\Models\Customer;

class CustomerDbRepository implements ICustomerRepository
{

    public function getAll(): array
    {
        return Customer::all()->toArray();
    }

    public function findById(string $id): ?Customer
    {
        return Customer::find($id);
    }

    public function create(Customer $customer): void
    {
        $customer->save();
    }

    public function update(Customer $customer): void
    {
        $customer->save();
    }

    public function delete(string $id): bool
    {
        $customer = Customer::find($id);
        return (bool)$customer?->delete();
    }
}
