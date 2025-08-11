<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;

class CustomerCachedRepository implements ICustomerRepository
{
    public function __construct(protected ICustomerRepository $customerRepository)
    {

    }

    public function getAll(): array
    {
        return Cache::remember($this->getKey() . 'all', 60, function () {
            return $this->customerRepository->getAll();
        });
    }

    public function findById(string $id): ?Customer
    {
        return Cache::remember($this->getKey() . $id, 60, function () use ($id) {
            return $this->customerRepository->findById($id);
        });
    }

    public function create(Customer $customer): void
    {
        $this->customerRepository->create($customer);
        Cache::forget($this->getKey() . 'all');
    }

    public function update(Customer $customer): void
    {
        $this->customerRepository->update($customer);
        Cache::forget($this->getKey() . 'all');
        Cache::forget($this->getKey() . $customer->id);
    }

    public function delete(string $id): bool
    {
        $result = $this->customerRepository->delete($id);
        if ($result) {
            Cache::forget($this->getKey() . 'all');
            Cache::forget($this->getKey() . $id);
        }
        return $result;
    }

    private function getKey(): string
    {
        return "customer_";
    }
}
