<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Custom\APIInsert;


class InsertAPIData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from the REST API and inserts into the database.';

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

        $api_insert = new APIInsert();
        
        $api_insert->InsertAll(true);

        return 0;
        
    }
}
