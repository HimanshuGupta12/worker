<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hour;
use Illuminate\Support\Facades\DB;

class addWorkerPerformaceParamsInHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_worker_performance_params';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-calculate workers performance parameters in hours registraion';

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
        $hours = Hour::all();
        
        foreach ($hours as $hour) {
            $wordsCount = isset($hour->comments) ? str_word_count($hour->comments) : 0;
            $imagesCount = isset($hour->images) ? count($hour->images) : 0;
            $hoursCount = Hour::lateSubmissionHours($hour->end_time, $hour->work_day, $hour->created_at);
            $this->comment("Id : $hour->id, words : $wordsCount, images : $imagesCount, hours : $hoursCount");
            DB::table('hours')
              ->where('id', $hour->id)
              ->update(['late_submission_hours' => $hoursCount, 'no_of_words_in_comments' => $wordsCount, 'no_of_images' => $imagesCount]);
        }
        
        return 0;
    }
}
