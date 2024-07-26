<?php

namespace App\Jobs\ErpSync;

use App\Models\Address;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchAddresses implements ShouldQueue
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
        ->table('BPADDRESS AS BPA')
        ->select(
            'BPA.BPANUM_0 as customer_id',
            'BPA.BPAADD_0 as erp_id',
            'BPA.BPADES_0 as description',
            'BPA.BPAADDLIG_0 as address',
            'BPA.POSCOD_0 as cap',
            'BPA.CTY_0 as city',
            'BPA.SAT_0 as province',
            'BPA.CRY_0 as country',
        );

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'AX.UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Addresses with "' . $this->fetchMode . '" mode.');

        $addresses = $query->get();

        Log::debug('Fetched ' . $addresses->count() . ' records.');

        foreach ($addresses as $address) {
            Address::upsert(
                [
                    'erp_id' => $address->erp_id,
                    'customer_id' => $address->customer_id,
                    'address' => $address->address,
                    'description' => $address->description,
                    'cap' => $address->cap,
                    'city' => $address->city,
                    'province' => $address->province,
                    'country' => $address->country,
                ],
                [
                    'erp_id',
                    'customer_id',
                ]
            );
        }
    }
}
