<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        // $conn = new ErpConnectorImpl();
        // $conn->createProspect();
        Log::debug("starting job");
        $job = new FetchCategoryScopes();
        $job::dispatch();
    }
}
