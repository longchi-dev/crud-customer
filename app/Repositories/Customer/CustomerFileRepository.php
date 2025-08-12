<?php

namespace App\Repositories\Customer;

use App\Models\Customer;

class CustomerFileRepository implements ICustomerRepository
{
    public function __construct(private string $filePath)
    {
        $this->filePath = config('customer.file_path');
    }

    public function getAllCustomers(): array
    {
        $rows = $this->readFile();

        return array_map(fn($row) => new Customer([
            'uuid' => $row['uuid'],
            'name' => $row['name'],
            'totalAmount' => $row['totalAmount'],
            'vipLevel' => $row['vipLevel'],
            'createdBy' => $row['createdBy'],
        ]), $rows);
    }

    public function findCustomerById(string $uuid): ?Customer
    {
        foreach ($this->readFile() as $row) {
            if ($row['uuid'] === $uuid) {
                return new Customer([
                    'uuid' => $row['uuid'],
                    'name' => $row['name'],
                    'totalAmount' => $row['totalAmount'],
                    'vipLevel' => $row['vipLevel'],
                    'createdBy' => $row['createdBy'],
                ]);
            }
        }
        return null;
    }

    public function createCustomer(Customer $customer): void
    {
        $rows = $this->readFile();

        $rows[] = [
            'uuid' => $customer->uuid,
            'name' => $customer->name,
            'totalAmount' => $customer->totalAmount,
            'vipLevel' => $customer->vipLevel,
            'createdBy' => $customer->createdBy,
        ];
        $this->writeFile($rows);
    }

    public function saveCustomer(Customer $customer): void
    {
        $rows = $this->readFile();

        foreach ($rows as &$row) {
            if ($row['uuid'] === $customer->uuid) {
                $row['name'] = $customer->name;
                $row['totalAmount'] = $customer->totalAmount;
                $row['vipLevel'] = $customer->vipLevel;
                $row['createdBy'] = $customer->createdBy;
                break;
            }
        }

        $this->writeFile($rows);
    }

    public function deleteCustomer(string $uuid): bool
    {
        $rows = $this->readFile();
        $originalCount = count($rows);
        $rows = array_filter($rows, fn($row) => $row['uuid'] !== $uuid);
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
}
