<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Custom\APIUpdate;

class UpdateAPIData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from the REST API and updates the existing entries in database.';

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
     *
     * @return int
     */
    public function handle()
    {
        $api_update = new APIUpdate();
        
        $api_update->UpdateAll(true);
        return 0;
    }
}
