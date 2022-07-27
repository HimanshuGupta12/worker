<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tool;
use App\Models\Company;

class CorrectManualToolIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tool_id:manually_added';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct all the manually added tool Ids in old data company-wise.';

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
        $companyIds = Company::pluck('id');
        $updateToolIds = [];
        foreach ($companyIds as $key => $companyId) {
            
            $manualFilledIds = Tool::whereNull('tool_code')->where('company_id', $companyId)->withTrashed()->orderBy('company_tool_id')->pluck('company_tool_id')->toArray();
            $allFilledIds = Tool::where('company_id', $companyId)->withTrashed()->pluck('company_tool_id')->toArray();
            $limit = count($allFilledIds)+1;
            
            $wrongToolId = [];
            $correctToolId = [];
            for ($i = 1; $i <= $limit; $i++) {
                if (isset($manualFilledIds[$i-1])) {
                    if ($manualFilledIds[$i-1] != $i) {
                        $wrongToolId[] = $manualFilledIds[$i-1];
                    }else{
                        $correctToolId[] = $manualFilledIds[$i-1];
                    }
                }
            }
            
            $wrongToolIdCount = count($wrongToolId);
            
            $newToolId = [];
            $updateStart = count($correctToolId)+1;
            
            for ($i = $updateStart; $i < $limit; $i++) {
                if (in_array($i, $wrongToolId) && count($newToolId) < $wrongToolIdCount) {
                    $newToolId[] = $i;
                } else {
                    if (!in_array($i, $allFilledIds) && count($newToolId) < $wrongToolIdCount) {
                        $newToolId[] = $i;
                    }
                }
            }
            $this->comment(++$key.' Company name : '. Company::find($companyId)->name );
            $this->comment('Wrong Tool Ids = '. print_r($wrongToolId, true));
            $this->comment('New Tool Ids will be = '. print_r($newToolId, true));
            if ($this->confirm('Do you want to over-write the old Tool Ids with new ones..?')) {
            
                for ($i = 0; $i < $wrongToolIdCount; $i++) {
                    if ($this->confirm('Tool Id #'.$wrongToolId[$i].'# will be replaced by #'.$newToolId[$i].'#. Do you wish to continue', true)) {
                        $tool = Tool::where('company_tool_id', $wrongToolId[$i])->where('company_id', $companyId)->withTrashed()->select('id', 'company_tool_id', 'company_id')->get()->toArray();
                        print_r($tool);
                        if (count($tool)) {
                            Tool::where('id', $tool[0]['id'])->where('company_id', $companyId)->withTrashed()->update(['company_tool_id' => $newToolId[$i]]);
                        }
                    }
                }
            $this->comment('The tool Ids have been updated successfully!');
            } else {
                $this->comment('Please try next time!');
            }
        }
    }
}
