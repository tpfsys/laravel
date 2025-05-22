<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\UpCommand as BaseUpCommand;

class UpCommand extends BaseUpCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Call the parent handle method to maintain all existing functionality
        // This will remove the maintenance.php file entirely, effectively clearing the message as well
        return parent::handle();
    }
}

