<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;

class ClientApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:api
                {action=store : use index to retrieve or store to persist the API}
                {--page=1 : page offset, all api calls start from page 1, unless otherwise}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve or persist the Client API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * for each Client API model delegate to the handle method
     * by default action = index
     */
    public function handle()
    {
        Client::all()->each->handle(
            $this->argument('action'),
            $this->option ('page')
        );

        $this->info('Client API command complete');
    }
}
