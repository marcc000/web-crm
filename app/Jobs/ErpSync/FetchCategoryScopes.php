<?php

namespace App\Jobs\ErpSync;

use App\Models\CategoryScope;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchCategoryScopes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $fetchMode = "partial")
    {
        $this->onQueue('erp_sync');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = DB::connection("erp")
            ->table("ATEXTRA")
            ->select("IDENT1_0 as key",
                "TEXTE_0 as description",
                "TEXTE_0 as category_scope_id",
            )
            ->where([
                ['CODFIC_0','ATABTAB'],
                ['LANGUE_0','ITA'],
                ['ZONE_0','LNGDES'],
            ])
            ->whereIn(
                'IDENT1_0',['23']
            );
        Log::debug("queried db");
        $scopes = $query->get();
        Log::debug(var_dump($scopes));
        foreach($scopes as $scope) {
            CategoryScope::updateOrCreate(
                ['key' => $scope->key],
                ['description' => $scope->description,
                'category_scope_id' => $scope->category_scope_id,]
            );
        }
    }
}
