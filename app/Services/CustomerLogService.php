<?php

namespace App\Services;

class CustomerLogService
{
    private static ?CustomerLogService $instance = null;
    private string $logFile;

    private function __construct()
    {
        $this->logFile = storage_path('logs/customer.log');
    }

    public static function getInstance(): CustomerLogService
    {
        if (self::$instance === null) {
            self::$instance = new CustomerLogService();
        }
        return self::$instance;
    }

    public function log(string $action, array $data, string $user): void
    {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'user' => $user,
            'data' => $data,
        ];

        $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

        file_put_contents($this->logFile, $line, FILE_APPEND | LOCK_EX);
    }

    private function __clone() {}
}
