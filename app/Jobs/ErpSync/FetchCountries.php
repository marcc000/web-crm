<?php

namespace App\Jobs\ErpSync;

use App\Models\Country;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchCountries implements ShouldQueue
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
                'IDENT1_0 as ISO',
                'TEXTE_0 as name',
            )
            ->where([
                ['CODFIC_0', 'TABCOUNTRY'],
                ['LANGUE_0', 'ITA'],
                ['ZONE_0', 'CRYDES'],
            ])
            ->whereIn(
                'IDENT1_0',
                [
                    'IT', //Italia
                    'SM', //San Marino
                ]
            );

        Log::debug('Fetching Countries with "'.$this->fetchMode.'" mode.');

        $countries = $query->get();

        Log::debug('Fetched '.$countries->count().' records.');

        foreach ($countries as $country) {
            Country::upsert([
                'ISO' => $country->ISO,
                'name' => $country->name,
            ],
                [
                    'ISO', ]
            );
        }
    }
}
