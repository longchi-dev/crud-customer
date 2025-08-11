<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CustomerLogService;
use App\Services\CustomerService;
use Illuminate\Console\Command;

class CustomerCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer {action : list|find|create|update|delete} {--id= : Customer ID} {--name= : Customer Name} {--amount= : Total Amount} {--createdBy= : Creator Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD Customer API';

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $logService = CustomerLogService::getInstance();
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
                $createdBy = $this->option('createdBy');

                if (!$name || !$amount || !$createdBy) {
                    $this->error('Please provide --name, --amount, and --createdBy options.');
                    return;
                }

                $customer = $this->customerService->create(
                    new Customer([
                        'id' => '',
                        'name' => $name,
                        'total_amount' => (float)$amount,
                        'vip_level' => 0,
                        'created_by' => $createdBy,
                    ])
                );

                $logService->log('create', $customer->toArray(), $createdBy);
                $this->info("Customer created:\n" . json_encode($customer, JSON_PRETTY_PRINT));
                break;

            case 'update':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('Please provide --id option.');
                    return;
                }
                $customer = $this->customerService->findById($id);
                if (!$customer) {
                    $this->error("Customer with id $id not found.");
                    return;
                }

                $name = $this->option('name');
                $amount = $this->option('amount');

                if ($name) $customer->name = $name;
                if ($amount) $customer->total_amount = (float)$amount;

                $updatedCustomer = $this->customerService->update($customer);

                $logService->log('update', $updatedCustomer->toArray(), $updatedCustomer->created_by);
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
                    $logService->log('delete', ['id' => $id], 'unknown');
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
