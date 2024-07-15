<?php

namespace App\Jobs\ErpSync;

use App\Models\Province;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchProvinces implements ShouldQueue
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
            ->table('ATEXTRA')
            ->select(
                'IDENT2_0 as ISO',
                'TEXTE_0 as name',
            )
            ->where([
                ['CODFIC_0', 'TABSUBDIV'],
                ['LANGUE_0', 'ITA'],
                ['IDENT1_0', 'IT~3'],
            ]);

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Provinces with "'.$this->fetchMode.'" mode.');

        $provinces = $query->get();

        Log::debug('Fetched '.$provinces->count().' records.');

        foreach ($provinces as $province) {
            Province::upsert(
                [
                    'ISO' => $province->ISO,
                    'name' => $province->name,
                    'country' => 'IT',
                ],
                [
                    'ISO',
                ]
            );
        }
    }
}
