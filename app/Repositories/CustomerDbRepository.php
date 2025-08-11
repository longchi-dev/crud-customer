<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerDbRepository extends CustomerRepository implements ICustomerRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function findById(string $id): ?Customer
    {
        // TODO: Implement findById() method.
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }

    protected function persistCreate(Customer $customer): void
    {
        // TODO: Implement persistCreate() method.
    }

    protected function persistUpdate(Customer $customer): void
    {
        // TODO: Implement persistUpdate() method.
    }
}
