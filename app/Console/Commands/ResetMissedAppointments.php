<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetMissedAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:reset-missed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $now = Carbon::now();

        Event::whereNull('attended')
            ->where(function ($q) use ($now) {
                $q->where('date', '<', $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('date', $now->toDateString())
                            ->where('endtime', '<', $now->format('H:i:s'));
                    });
            })
            ->update(['attended' => false]);

        $this->info('Citas vencidas actualizadas correctamente.');
    }
}
