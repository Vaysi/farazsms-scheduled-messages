<?php

namespace App\Console;

use App\Event;
use App\Message;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        $schedule->call(function (){
            $events = Event::whereStatus('pending')->oldest()->take(5)->get();
            foreach ($events as $event){
                if(!isActive(false,$event->user)){
                    continue;
                }
                $numbers = $event->contact->numbers;
                foreach ($numbers as $number){
                    $fireOn = Carbon::parse($number->updated_at)->addHours(intval($event->hour))->addDays(intval($event->day))->addMinutes(intval($event->minute))->addMonths(intval($event->month))->addYears(intval($event->year));
                    if(now() > $fireOn){
                        $msg = $event->msg;
                        $msg = str_replace("%time%",now('Asia/Tehran')->format('H:i:s'),$msg);
                        sendSms($number->number,$event->from,$msg,$event->user->api_username,$event->user->api_password);
                        Message::create([
                            'number_id' => $number->id,
                            'user_id' => $event->id,
                            'content' => $msg
                        ]);
                        $number->touch();
                    }
                }
                $event->touch();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
