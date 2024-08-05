<?php

namespace App\Jobs\ErpSync;

use App\Models\Agent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchAgents implements ShouldQueue
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
            ->table('SALESREP AS REP1')
            ->join('SALESREP AS REP2', 'REP1.REPNUM_0', '=', 'REP2.YREPNUM_0')
            ->select(
                'REP1.REPNUM_0 as agent_id',
                'REP1.REPNAM_0 as name',
                'REP2.REPNUM_0 as zone',
            )
            ->where('REP1.YTYPREP_0', '1')
            ->orderBy('REP1.REPNUM_0');

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'REP1.UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Agents with "'.$this->fetchMode.'" mode.');

        $agents = $query->get();

        Log::debug('Fetched '.$agents->count().' records.');

        foreach ($agents as $agent) {
            Agent::upsert(
                [
                    'agent_id' => $agent->agent_id,
                    'zone' => $agent->zone,
                    'name' => $agent->name,
                ],
                [
                    'agent_id',
                    'zone',
                ]
            );
        }
    }
}
