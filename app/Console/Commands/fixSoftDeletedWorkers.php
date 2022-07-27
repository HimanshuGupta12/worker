<?php

namespace App\Console\Commands;

use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class fixSoftDeletedWorkers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worker:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete all workers which are soft deleted already. While deleted assign their tools to any of their storages.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $companyIds = DB::table('workers')->whereNotNull('deleted_at')->select('company_id')->distinct()->get();
            foreach ($companyIds as $company) {
                $workers = DB::table('workers')->whereNotNull('deleted_at')->where('company_id', $company->company_id)->select('id', 'company_id', 'deleted_at')->get();
                $storage = DB::table('storages')->where('company_id', $company->company_id)->select('id', 'company_id', 'name')->first();
                foreach($workers as $worker) {
                    \Illuminate\Support\Facades\Log::info('worker id = '. $worker->id);
                    $this->line('worker id = '. $worker->id);
                    $data = ['possessor_id' => $storage->id, 'possessor_type' => 'App\Models\Storage'];
                    DB::table('tools')->where('possessor_id', $worker->id)->where('possessor_type', 'App\Models\Worker')->update($data);
                    DB::table('workers')->where('id', $worker->id)->delete();
                }
            }
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
