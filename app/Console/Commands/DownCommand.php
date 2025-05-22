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
     * Create a maintenance file for the application.
     *
     * @return void
     */
    protected function createMaintenanceFile()
    {
        // First, we'll create the payload array with all the standard options
        $payload = $this->getMaintenancePayload();

        // Add the custom message if provided
        if ($this->option('message')) {
            $payload['message'] = $this->option('message');
        }

        // Create the down file with the payload
        file_put_contents(
            storage_path('framework/down'),
            json_encode($payload, JSON_PRETTY_PRINT)
        );

        // Create the maintenance.php file from our template
        file_put_contents(
            storage_path('framework/maintenance.php'),
            file_get_contents(storage_path('framework/maintenance-template.php'))
        );

        $this->components->info('Application is now in maintenance mode.');

        if ($this->option('message')) {
            $this->components->info('Maintenance message: '.$this->option('message'));
        }
    }
}

