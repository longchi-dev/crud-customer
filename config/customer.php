<?php
return [
    'storage' => env('CUSTOMER_STORAGE', 'file'),
    'file_path' => env('CUSTOMERS_FILE_PATH', storage_path('app/customer.txt')),
];
