<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;

class CustomerCachedRepository implements ICustomerRepository
{
    public function __construct(protected ICustomerRepository $customerRepository)
    {

    }

    public function getAllCustomers(): array
    {
        return Cache::remember($this->getKey() . 'all', 60, function () {
            return $this->customerRepository->getAllCustomers();
        });
    }

    public function findCustomerById(string $uuid): ?Customer
    {
        return Cache::remember($this->getKey() . $uuid, 60, function () use ($uuid) {
            return $this->customerRepository->findCustomerById($uuid);
        });
    }

    public function createCustomer(Customer $customer): void
    {
        $this->customerRepository->createCustomer($customer);
        Cache::forget($this->getKey() . 'all');
    }

    public function saveCustomer(Customer $customer): void
    {
        $this->customerRepository->saveCustomer($customer);
        Cache::forget($this->getKey() . 'all');
        Cache::forget($this->getKey() . $customer->uuid);
    }

    public function deleteCustomer(string $uuid): bool
    {
        $result = $this->customerRepository->deleteCustomer($uuid);
        if ($result) {
            Cache::forget($this->getKey() . 'all');
            Cache::forget($this->getKey() . $uuid);
        }
        return $result;
    }

    private function getKey(): string
    {
        return "customer_";
    }
}
