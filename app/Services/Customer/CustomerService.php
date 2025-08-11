<?php

namespace App\Services\Customer;

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Models\Customer;
use App\Repositories\Customer\ICustomerRepository;

class CustomerService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ICustomerRepository $customerRepository)
    {

    }

    public function getAll(): array
    {
        return $this->customerRepository->getAll();
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function getById(string $id): Customer
    {
        $customer = $this->customerRepository->findById($id);
        if (!$customer) {
            throw new CustomerNotFoundException();
        }
        return $customer;
    }

    public function create(array $data): Customer
    {
        $customer = Customer::make($data);
        $this->customerRepository->create($customer);
        return $customer;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function update(string $id, array $data): ?Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            throw new CustomerNotFoundException();
        }

        if(isset($data['name'])) {
            $customer->name = $data['name'];
        }

        if(isset($data['totalAmount'])) {
            $customer->totalAmount = $data['totalAmount'];
        }

        $this->customerRepository->update($customer);
        return $customer;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function delete(string $id): bool
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            throw new CustomerNotFoundException();
        }

        return $this->customerRepository->delete($id);
    }
}
