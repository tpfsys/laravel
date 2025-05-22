<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\DownCommand as BaseDownCommand;
use Illuminate\Support\Facades\File;

class DownCommand extends BaseDownCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'down
                          {--redirect= : The path that users should be redirected to}
                          {--render= : The view that should be prerendered for display during maintenance mode}
                          {--refresh= : The number of seconds after which the request will be refreshed}
                          {--retry= : The number of seconds after which the request may be retried}
                          {--secret= : The secret phrase that can be used to bypass maintenance mode}
                          {--status=503 : The status code that should be used when returning the maintenance mode response}
                          {--message= : The message to display during maintenance mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the application into maintenance mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Call the parent handle method to maintain all existing functionality
        $result = parent::handle();

        // If the parent command was successful and we have a message option
        if ($result === 0 && $this->option('message')) {
            $this->setMaintenanceMessage($this->option('message'));
        }

        return $result;
    }

    /**
     * Set the maintenance message in the payload.
     *
     * @param  string  $message
     * @return void
     */
    protected function setMaintenanceMessage($message)
    {
        $maintenanceFilePath = storage_path('framework/maintenance.php');

        if (File::exists($maintenanceFilePath)) {
            $content = File::get($maintenanceFilePath);
            
            // Extract the payload from the maintenance file
            if (preg_match('/\$data\s*=\s*(\[.+?\]);/s', $content, $matches)) {
                $payload = $matches[1];
                
                // Add or update the message in the payload
                if (strpos($payload, "'message'") !== false) {
                    $payload = preg_replace("/'message'\s*=>\s*'.*?'/", "'message' => '" . addslashes($message) . "'", $payload);
                } else {
                    $payload = str_replace(']', ", 'message' => '" . addslashes($message) . "']", $payload);
                }
                
                // Update the maintenance file with the new payload
                $content = preg_replace('/\$data\s*=\s*\[.+?\];/s', "\$data = {$payload};", $content);
                File::put($maintenanceFilePath, $content);
                
                $this->components->info("Application is now in maintenance mode with custom message.");
            }
        }
    }
}