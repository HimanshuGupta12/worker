<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Project;

class AddCompanyProjectIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:company_project_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add company project Id field in each project according to company';
    

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
        foreach ($companyIds as $companyId) {
            $projects = Project::where('company_id', $companyId)->get();
            $i = 1;
            foreach ($projects as $key => $proj) {
                $proj->update(['company_project_id' => $i++]);
            }
            $this->comment('Company Project Ids added for company Id : '. $companyId);
        }
        return 0;
    }
}
