<?php

namespace App\Jobs\ErpSync;

use App\Models\AgentZone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchAgentZones implements ShouldQueue
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
    private $freshTimeOffset = 120;

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
            ->table('SALESREP AS REP')
            ->select(
                'REP.REPNUM_0 as zone_id',
                'REP.YREPNUM_0 as agent_id',
            )
            ->where('YTYPREP_0', '2');

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'REP.UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Agent Zones with "'.$this->fetchMode.'" mode.');

        $zones = $query->get();

        Log::debug('Fetched '.$zones->count().' records.');

        foreach ($zones as $zone) {
            AgentZone::upsert(
                [
                    'zone_id' => $zone->zone_id,
                    'agent_id' => $zone->agent_id,
                ],
                [
                    'zone_id',
                    'agent_id',
                ]
            );
        }
    }
}
