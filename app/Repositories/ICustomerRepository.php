<?php

namespace App\Repositories;

use App\Models\Customer;

interface ICustomerRepository
{
    public function getAll(): array;
    public function findById(string $id): ?Customer;
    public function create(Customer $customer): void;
    public function update(string $id, array $data): void;
    public function delete(string $id): bool;
}
