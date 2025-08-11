<?php

namespace App\Repositories\Customer;

use App\Models\Customer;

class CustomerFileRepository implements ICustomerRepository
{
    public function __construct(private string $filePath)
    {
        $this->filePath = config('customers.file_path');
    }

    public function getAll(): array
    {
        $rows = $this->readFile();

        return array_map(fn($row) => new Customer([
            'id' => $row['id'],
            'name' => $row['name'],
            'totalAmount' => $row['totalAmount'],
            'vipLevel' => $row['vipLevel'],
            'createdBy' => $row['createdBy'],
        ]), $rows);
    }

    public function findById(string $id): ?Customer
    {
        foreach ($this->readFile() as $row) {
            if ($row['id'] === $id) {
                return new Customer([
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'totalAmount' => $row['totalAmount'],
                    'vipLevel' => $row['vipLevel'],
                    'createdBy' => $row['createdBy'],
                ]);
            }
        }
        return null;
    }

    public function create(Customer $customer): void
    {
        $rows = $this->readFile();
        $vipLevel = $this->calculateVipLevel($customer->totalAmount);

        $rows[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'totalAmount' => $customer->totalAmount,
            'vipLevel' => $vipLevel,
            'createdBy' => $customer->createdBy,
        ];
        $this->writeFile($rows);
    }

    public function update(Customer $customer): void
    {
        $rows = $this->readFile();

        foreach ($rows as &$row) {
            if ($row['id'] === $customer->id) {
                $row['name'] = $customer->name;
                $row['totalAmount'] = $customer->totalAmount;
                $row['vipLevel'] = $this->calculateVipLevel($customer->totalAmount);
                $row['createdBy'] = $customer->createdBy;
                break;
            }
        }

        $this->writeFile($rows);
    }


    public function delete(string $id): bool
    {
        $rows = $this->readFile();
        $originalCount = count($rows);
        $rows = array_filter($rows, fn($row) => $row['id'] !== $id);
        $this->writeFile(array_values($rows));

        return count($rows) < $originalCount;
    }

    private function readFile(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        return json_decode(file_get_contents($this->filePath), true) ?? [];
    }

    private function writeFile(array $data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    }

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
