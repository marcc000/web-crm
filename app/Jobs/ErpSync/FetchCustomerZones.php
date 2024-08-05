<?php

namespace App\Jobs\ErpSync;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchCustomerZones implements ShouldQueue
{
    use Queueable;

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
    private $freshTimeOffset = 1440;

    /**
     * Create a new job instance.
     *
     * @param  string  $fetchMode
     *                             'fresh' to read only recently
     *                             updated records,
     *                             'all' to read all records
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
            ->table('YBPCREP AS YREP')
            ->select(
                'YREP.BPCNUM_0 as customer_id',
                'YREP.REP2_0 as zone_id',
            )
            ->distinct()
            ->where('YREP.REP2_0', '!=', 'ZZ');

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Customer-zone relationships with "'.$this->fetchMode.'" mode.');

        $relations = $query->get();

        Log::debug('Fetched '.$relations->count().' records.');

        foreach ($relations as $relation) {
            DB::table('customer_zone')->upsert(
                [
                    'zone_id' => $relation->zone_id,
                    'customer_id' => $relation->customer_id,
                ],
                [
                    'zone_id',
                    'customer_id',
                ]
            );
        }
    }
}
