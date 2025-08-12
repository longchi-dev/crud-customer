<?php

namespace App\Repositories\Customer;

use App\Models\Customer;

interface ICustomerRepository
{
    public function getAllCustomers(): array;
    public function findCustomerById(string $uuid): ?Customer;
    public function createCustomer(Customer $customer): void;
    public function saveCustomer(Customer $customer): void;
    public function deleteCustomer(string $uuid): bool;
}
