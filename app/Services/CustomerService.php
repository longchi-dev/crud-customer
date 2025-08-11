<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\ICustomerRepository;
use Illuminate\Support\Str;

class CustomerService
{
    private ICustomerRepository $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAll(): array
    {
        return $this->customerRepository->getAll();
    }

    public function findById(string $id): ?Customer
    {
        return $this->customerRepository->findById($id);
    }

    public function create(Customer $customer): Customer
    {
        if (!$customer->id) {
            $customer->id = (string) Str::uuid();
        }

        $this->customerRepository->create($customer);

        return $customer;
    }

    public function update(Customer $customer): ?Customer
    {
        $existCustomer = $this->customerRepository->findById($customer->id);

        if (!$existCustomer) {
            return null;
        }

        $existCustomer->name = $customer->name ?? $existCustomer->name;
        $existCustomer->total_amount = $customer->total_amount ?? $existCustomer->total_amount;
        $this->customerRepository->update($existCustomer);
        return $existCustomer;
    }

    public function delete(string $id): bool
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return false;
        }

        $this->customerRepository->delete($id);

        return true;
    }
}
