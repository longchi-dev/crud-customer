<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerFileRepository extends CustomerRepository implements ICustomerRepository
{
    private string $filePath;

    public function __construct()
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

    public function delete(string $id): void
    {
        $rows = array_filter($this->readFile(), fn($row) => $row['id'] !== $id);
        $this->writeFile(array_values($rows));
    }

    protected function persistCreate(Customer $customer): void
    {
        if (!$customer->id) {
            $customer->id = Str::uuid()->toString();
        }

        $rows = $this->readFile();
        $rows[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'total_amount' => $customer->total_amount,
            'vip_level' => $customer->vip_level,
            'created_by' => $customer->created_by,
        ];
        $this->writeFile($rows);
    }

    protected function persistUpdate(Customer $customer): void
    {
        $rows = $this->readFile();
        foreach ($rows as &$row) {
            if ($row['id'] === $customer->id) {
                $row['name'] = $customer->name;
                $row['total_amount'] = $customer->total_amount;
                $row['vip_level'] = $customer->vip_level;
                $row['created_by'] = $customer->created_by;
                break;
            }
        }
        $this->writeFile($rows);
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
