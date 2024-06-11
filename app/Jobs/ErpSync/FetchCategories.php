<?php

namespace App\Jobs\ErpSync;

use App\Models\Category;
use App\Models\CategoryScope;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchCategories implements ShouldQueue
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
        ->table('ATEXTRA AS AX')
        ->select(
            'AX.IDENT2_0 as key',
            'AX.TEXTE_0 as description',
            'AX.IDENT1_0 as category_scope',
            'AT.DEPCOD_0 as parent_category',
        )
        ->join('ATABDIV AS AT', function ($join) {
            $join->on('AT.NUMTAB_0', '=', 'AX.IDENT1_0');
            $join->on('AT.CODE_0', '=', 'AX.IDENT2_0');
        })
        ->where([
            ['AX.CODFIC_0', 'ATABDIV'],
            ['AX.LANGUE_0', 'ITA'],
            ['AX.ZONE_0', 'LNGDES'],
        ])
        ->whereIn(
            'AX.IDENT1_0',
            CategoryScope::select('key')->distinct()->get()
        );

        if ($this->fetchMode == 'fresh') $query->where(
            'AX.UPDDATTIM_0',
            '>=',
            Carbon::now()->subMinutes($this->freshTimeOffset)->toDateTimeString()
        );

        Log::debug('Fetching Categories with "' . $this->fetchMode . '" mode.');

        $categories = $query->get();

        Log::debug('Fetched ' . $categories->count() . ' records.');

        foreach ($categories as $category) {
            Category::upsert(
                [
                    'key' => $category->key,
                    'category_scope' => $category->category_scope,
                    'description' => $category->description,
                    'parent_category' => $category->parent_category,
                ],
                [
                    'key',
                    'category_scope',
                ]
            );
        }
    }
}
