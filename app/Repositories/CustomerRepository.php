<?php

namespace App\Repositories;

use App\Models\Customer;

abstract class CustomerRepository implements ICustomerRepository
{
    abstract public function getAll(): array;

    abstract public function findById(string $id): ?Customer;

    abstract public function delete(string $id): void;

    public function create(Customer $customer): void
    {
        $vipLevel = $this->calculateVipLevel($customer->total_amount);
        $customer->vip_level = $vipLevel;
        $this->persistCreate($customer);
    }

    public function update(Customer $customer): void
    {
        $vipLevel = $this->calculateVipLevel($customer->total_amount);
        $customer->vip_level = $vipLevel;
        $this->persistUpdate($customer);
    }

    abstract protected function persistCreate(Customer $customer): void;

    abstract protected function persistUpdate(Customer $customer): void;

    protected function calculateVipLevel(float $totalAmount): int
    {
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
