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
        return $this->customerRepository->getAllCustomers();
    }

    public function getById(string $uuid): ?Customer
    {
        return $this->customerRepository->findCustomerById($uuid);
    }

    public function create(string $name, float $totalAmount): Customer
    {
        $vipLevel = $this->calculateVipLevel($totalAmount);

        $customer = Customer::make(
            $name,
            $totalAmount,
            $vipLevel
        );

        $this->customerRepository->createCustomer($customer);
        return $customer;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function update(
        string $uuid,
        ?string $name = null,
        ?float $totalAmount = null
    ): ?Customer
    {
        $customer = $this->customerRepository->findCustomerById($uuid);

        if(!$customer) {
            throw new CustomerNotFoundException();
        }

        if ($name !== null) {
            $customer->name = $name;
        }

        if ($totalAmount !== null) {
            $customer->totalAmount = $totalAmount;
            $customer->vipLevel = $this->calculateVipLevel($totalAmount);
        }

        $this->customerRepository->saveCustomer($customer);
        return $customer;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function delete(string $uuid): bool
    {
        $deleteCustomer = $this->customerRepository->deleteCustomer($uuid);

        if (!$deleteCustomer) {
            throw new CustomerNotFoundException();
        }

        return true;
    }

    private function calculateVipLevel(float $totalAmount)  : int {
        $vipConfig = config('vip', []);
        $level = 0;

        foreach ($vipConfig as $vip) {
            if ($totalAmount >= $vip['amount']) {
                $level = $vip['level'];
            }
        }

        return $level;
    }
}
