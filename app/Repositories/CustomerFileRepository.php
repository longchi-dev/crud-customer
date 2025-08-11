<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Str;

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
            'total_amount' => $row['total_amount'],
            'vip_level' => $row['vip_level'],
            'created_by' => $row['created_by'],
        ]), $rows);
    }

    public function findById(string $id): ?Customer
    {
        foreach ($this->readFile() as $row) {
            if ($row['id'] === $id) {
                return new Customer([
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'total_amount' => $row['total_amount'],
                    'vip_level' => $row['vip_level'],
                    'created_by' => $row['created_by'],
                ]);
            }
        }
        return null;
    }

    public function create(Customer $customer): void
    {
        $rows = $this->readFile();
        $vipLevel = $this->calculateVipLevel($customer->total_amount);

        $rows[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'total_amount' => $customer->total_amount,
            'vip_level' => $vipLevel,
            'created_by' => $customer->created_by,
        ];
        $this->writeFile($rows);
    }

    public function update(string $id, array $data): void
    {
        $rows = $this->readFile();

        foreach ($rows as &$row) {
            if ($row['id'] === $id) {
                if (isset($data['name'])) {
                    $row['name'] = $data['name'];
                }
                if (isset($data['total_amount'])) {
                    $row['total_amount'] = $data['total_amount'];
                    $row['vip_level'] = $this->calculateVipLevel($data['total_amount']);
                }
                if (isset($data['created_by'])) {
                    $row['created_by'] = $data['created_by'];
                }
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
