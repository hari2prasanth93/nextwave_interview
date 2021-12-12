<?php

namespace App\Console;

use App\Models\Score;
use App\Models\Tournament;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $tournament_list = Tournament::all();
            foreach($tournament_list as $tournament_datum){
                DB::select('update scores JOIN (SELECT @curGrade :=0) r set grade= (@curGrade := @curGrade+1) where tournament_id ='.$tournament_datum->id.' order by score DESC');
            }
        })->everyMinute();
    }
}
