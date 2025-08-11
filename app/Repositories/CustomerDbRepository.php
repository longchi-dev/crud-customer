<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerDbRepository implements ICustomerRepository
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

    public function create(Customer $customer): void
    {
        // TODO: Implement create() method.
    }

    public function update(string $id, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }
}
