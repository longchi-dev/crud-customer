<?php

namespace App\Console\Commands;

use App\Exceptions\Customer\CustomerNotFoundException;
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
    protected $signature = 'customer
        {action : list|find|create|update|delete}
        {--uuid= : Customer UUID}
        {--name= : Customer Name}
        {--amount= : Total Amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD Customer API';

    public function __construct(
        protected CustomerService $customerService,
        protected CustomerLogService $customerLogService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws CustomerNotFoundException
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
                $uuid = $this->option('uuid');

                if (!$uuid) {
                    $this->error('Please provide --uuid option.');
                    return;
                }

                $customer = $this->customerService->getById($uuid);
                $this->info(json_encode($customer, JSON_PRETTY_PRINT));
                break;

            case 'create':
                $name = $this->option('name');
                $amount = $this->option('amount');

                if (!$name || !$amount) {
                    $this->error('Please provide --name, --amount options.');
                    return;
                }

                $customer = $this->customerService->create($name, $amount);

                $this->customerLogService->log('create', $customer->toArray(), $name);
                $this->info("Customer created:\n" . json_encode($customer, JSON_PRETTY_PRINT));
                break;

            case 'update':
                $uuid = $this->option('uuid');

                if (!$uuid) {
                    $this->error('Please provide --uuid option.');
                    return;
                }

                $name = $this->option('name');
                $amount = $this->option('amount');


                if ($name === null && $amount === null) {
                    $this->error('Please provide at least one field to update: --name or --amount.');
                    return;
                }

                $updatedCustomer = $this->customerService->update($uuid, $name, $amount);

                $this->customerLogService->log('update', $updatedCustomer->toArray(), $updatedCustomer->createdBy);
                $this->info("Customer updated:\n" . json_encode($updatedCustomer, JSON_PRETTY_PRINT));
                break;

            case 'delete':
                $uuid = $this->option('uuid');

                if (!$uuid) {
                    $this->error('Please provide --uuid option.');
                    return;
                }

                $this->customerService->delete($uuid);

                $this->customerLogService->log('delete', ['uuid' => $uuid], 'unknown');
                $this->info("Customer with id $uuid deleted.");
                break;

            default:
                $this->error('Invalid action. Allowed actions: list, find, create, update, delete.');
                break;
        }
    }
}
