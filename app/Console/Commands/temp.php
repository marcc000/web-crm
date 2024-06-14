<?php

namespace App\Console\Commands;

use App\Implementations\Erp\ErpConnectorImpl;
use App\Jobs\ErpSync\FetchCategories;
use App\Jobs\ErpSync\FetchCategoryScopes;
use Illuminate\Console\Command;

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
        // $conn = new ErpConnectorImpl();
        // $conn->createProspect
        $job = new FetchCategoryScopes();
        $job::dispatch('all');
        $job = new FetchCategories();
        $job::dispatch('all');
    }
}
