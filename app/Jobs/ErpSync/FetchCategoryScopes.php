<?php

namespace App\Jobs\ErpSync;

use App\Models\CategoryScope;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchCategoryScopes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of tries before failing the job.
     *
     * @var int
     */
    public $tries = 1;
    /**
     * By default only fresh records are fetched,
     * a value different from 'fresh' will fetch
     * all records.
     */
    private $fetchMode;
    /**
     * Time offset of fresh records in minutes.
     */
    private $freshTimeOffset = 120;

    /**
     * Create a new job instance.
     * @param string $fetchMode
     * 'fresh' to read only recently
     * updated records,
     * 'all' to read all records
     */
    public function __construct(string $fetchMode = 'fresh')
    {
        $this->fetchMode = $fetchMode;
        $this->onQueue('erp_sync');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = DB::connection('erp')
            ->table('ATEXTRA')
            ->select('IDENT1_0 as key',
                'TEXTE_0 as description',
            )
            ->where([
                ['CODFIC_0', 'ATABTAB'],
                ['LANGUE_0', 'ITA'],
                ['ZONE_0', 'LNGDES'],
            ])
            ->whereIn(
                'IDENT1_0', ['23']
            );

        if($this->fetchMode == 'fresh') $query->where(
            'UPDDATTIM_0', '>=', Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
        );

        Log::debug('Fetching Category Scopes with "' . $this->fetchMode . '" mode.');

        $scopes = $query->get();

        Log::debug('Fetched ' . $scopes->count() . ' records.');

        foreach ($scopes as $scope) {
            CategoryScope::updateOrCreate(
                ['key' => $scope->key],
                ['description' => $scope->description,]
            );
        }
    }
}
