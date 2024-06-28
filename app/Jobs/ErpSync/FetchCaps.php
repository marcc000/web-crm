<?php

namespace App\Jobs\ErpSync;

use App\Models\Cap;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchCaps implements ShouldQueue
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
            ->table('POSCOD')
            ->select(
                'POSCOD_0 as code',
                'POSCTY_0 as city',
                'SATCOD_0 as province',
            )
            ->where('CRY_0','IT');

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Caps with "' . $this->fetchMode . '" mode.');

        $caps = $query->get();

        Log::debug('Fetched ' . $caps->count() . ' records.');

        foreach ($caps as $cap) {
            Cap::upsert(
                [
                    'code' => $cap->code,
                    'city' => $cap->city,
                    'province' => $cap->province,
                ],
                [
                    'code',
                ]
            );
        }
    }
}
