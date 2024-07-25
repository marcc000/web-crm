<?php

namespace App\Console\Commands;

use App\Jobs\ErpSync\FetchCaps;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ErpSync\FetchCountries;
use App\Jobs\ErpSync\FetchCustomers;
use App\Jobs\ErpSync\FetchProvinces;
use App\Jobs\ErpSync\FetchCategories;
use App\Jobs\ErpSync\FetchCategoryScopes;
use App\Implementations\Erp\ErpConnectorImpl;

class temp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$conn = new ErpConnectorImpl();
        //$conn->testOrder();

        Bus::chain([
            new FetchCategoryScopes('all'),
            new FetchCategories('all'),
            //new FetchCountries('all'),
            //new FetchProvinces('all'),
            //new FetchCaps('all'),
            //new FetchCustomers('all'),
        ])->dispatch();
    }
}
