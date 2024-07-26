<?php

namespace App\Jobs\ErpSync;

use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchCustomers implements ShouldQueue
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
            ->table('BPCUSTOMER AS BPC')
            ->leftJoin('BPARTNER AS BPR', 'BPC.BPCNUM_0', '=', 'BPR.BPRNUM_0')
            ->leftJoin('BPADDRESS AS BPA', function ($join) {
                $join->on('BPA.BPANUM_0', '=', 'BPC.BPCNUM_0');
                $join->where('BPA.BPAADD_0', '=', '000');
            })
            ->select(
                'BPC.BPCNUM_0 as erp_id',
                'BPC.BPCNAM_0 as business_name',
                'BPR.EECNUM_0 as vat_number',
                'BPC.BPAADD_0 as default_address',
                'BPC.BPDADD_0 as default_delivery_address',
                'BPC.CNTNAM_0 as default_contact',
                'BPC.YENAFLG_0 as exported',
                'BPC.TSCCOD_0 as price_list',
                'BPC.TSCCOD_1 as product_category',
                'BPC.TSCCOD_2 as sales_category',
                'BPC.TSCCOD_3 as channel',
                'BPC.TSCCOD_4 as seasonality',
                'BPC.PTE_0 as payment_method',
                'BPA.WEB_4 as PEC',
            )
            ->selectRaw('CASE WHEN BPC.BPCSTA_0 = 1 THEN BPC.UPDDATTIM_0 ELSE NULL END as active');

        if ($this->fetchMode == 'fresh') {
            $query->where(
                'UPDDATTIM_0',
                '>=',
                Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
            );
        }

        Log::debug('Fetching Customers with "'.$this->fetchMode.'" mode.');

        $customers = $query->get();

        Log::debug('Fetched '.$customers->count().' records.');

        foreach ($customers as $customer) {
            Customer::upsert(
                [
                    'erp_id' => $customer->erp_id,
                    'business_name' => $customer->business_name,
                    'vat_number' => $customer->vat_number,
                    'PEC' => $customer->PEC,
                    'default_address' => $customer->default_address,
                    'default_contact' => $customer->default_contact,
                    'exported' => $customer->exported - 1,
                    'price_list' => $customer->price_list,
                    'product_category' => $customer->product_category,
                    'sales_category' => $customer->sales_category,
                    'channel' => $customer->channel,
                    'seasonality' => $customer->seasonality,
                    'payment_method' => $customer->payment_method,
                    'default_delivery_address' => $customer->default_delivery_address,
                    'deleted_at' => $customer->active,
                ],
                [
                    'erp_id',
                ]
            );
        }
    }
}
