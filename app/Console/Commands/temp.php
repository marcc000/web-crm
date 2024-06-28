<?php

namespace App\Console\Commands;

use App\Jobs\ErpSync\FetchCaps;
use Illuminate\Console\Command;
use App\Jobs\ErpSync\FetchCountries;
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
        $job = new FetchCategoryScopes();
        $job::dispatch('all');
        $job = new FetchCategories();
        $job::dispatch('all');
        $job = new FetchCountries();
        $job::dispatch('all');
        $job = new FetchProvinces();
        $job::dispatch('all');
        $job = new FetchCaps();
        $job::dispatch('all');
    }
}
