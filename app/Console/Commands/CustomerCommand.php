<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\Customer\CustomerLogService;
use App\Services\Customer\CustomerService;
use Illuminate\Console\Command;

class CustomerCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer {action : list|find|create|update|delete} {--id= : Customer ID} {--name= : Customer Name} {--amount= : Total Amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD Customer API';

    public function __construct(protected CustomerService $customerService, protected CustomerLogService $customerLogService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $customers = $this->customerService->getAll();
                $this->info(json_encode($customers, JSON_PRETTY_PRINT));
                break;

            case 'find':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('Please provide --id option.');
                    return;
                }
                $customer = $this->customerService->findById($id);
                if (!$customer) {
                    $this->error("Customer with id $id not found.");
                } else {
                    $this->info(json_encode($customer, JSON_PRETTY_PRINT));
                }
                break;

            case 'create':
                $name = $this->option('name');
                $amount = $this->option('amount');

                if (!$name || !$amount) {
                    $this->error('Please provide --name, --amount options.');
                    return;
                }

                $customer = Customer::make([
                    'name' => $name,
                    'total_amount' => (float) $amount,
                    'vip_level' => 0,
                ]);


                $this->customerLogService->log('create', $customer->toArray(), $name);
                $this->info("Customer created:\n" . json_encode($customer, JSON_PRETTY_PRINT));
                break;

            case 'update':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('Please provide --id option.');
                    return;
                }

                $data = [];
                $name = $this->option('name');
                $amount = $this->option('amount');

                if ($name !== null) {
                    $data['name'] = $name;
                }

                if ($amount !== null) {
                    $data['total_amount'] = (float)$amount;
                }

                if (empty($data)) {
                    $this->error('Please provide at least one field to update: --name or --amount.');
                    return;
                }

                $updatedCustomer = $this->customerService->update($id, $data);

                if (!$updatedCustomer) {
                    $this->error("Customer with id $id not found.");
                    return;
                }

                $this->customerLogService->log('update', $updatedCustomer->toArray(), $updatedCustomer->created_by);
                $this->info("Customer updated:\n" . json_encode($updatedCustomer, JSON_PRETTY_PRINT));
                break;

            case 'delete':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('Please provide --id option.');
                    return;
                }

                $deleted = $this->customerService->delete($id);
                if ($deleted) {
                    $this->customerLogService->log('delete', ['id' => $id], 'unknown');
                    $this->info("Customer with id $id deleted.");
                } else {
                    $this->error("Customer with id $id not found.");
                }
                break;

            default:
                $this->error('Invalid action. Allowed actions: list, find, create, update, delete.');
                break;
        }
    }
}
