<?php

namespace App\Console\Commands;

use App\Implementations\Erp\ErpConnectorImpl;
use App\Jobs\ErpSync\FetchAddresses;
use App\Jobs\ErpSync\FetchAgentZones;
use App\Jobs\ErpSync\FetchCaps;
use App\Jobs\ErpSync\FetchCategories;
use App\Jobs\ErpSync\FetchCategoryScopes;
use App\Jobs\ErpSync\FetchCountries;
use App\Jobs\ErpSync\FetchCustomers;
use App\Jobs\ErpSync\FetchCustomerZones;
use App\Jobs\ErpSync\FetchProvinces;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

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
            new FetchCountries('all'),
            new FetchProvinces('all'),
            new FetchCaps('all'),
            new FetchCustomers('all'),
            new FetchAddresses('all'),
            new FetchAgentZones('all'),
            new FetchCustomerZones('all'),
        ])->dispatch();
    }
}
