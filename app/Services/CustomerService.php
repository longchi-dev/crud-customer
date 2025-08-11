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
        $this->customerRepository->create($customer);
        return $customer;
    }

    public function update(string $id, array $data): ?Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return null;
        }

        foreach ($data as $key => $value) {
            $customer->$key = $value;
        }

        $this->customerRepository->update($id, $data);
        return $customer;
    }


    public function delete(string $id): bool
    {
        return $this->customerRepository->delete($id);
    }
}
