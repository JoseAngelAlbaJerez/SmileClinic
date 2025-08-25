<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Patient;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-notifications';

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
    $patients = Patient::whereHas('appointments', function ($q) {
        $q->latest('date');
    })
    ->with(['appointments' => fn($q) => $q->latest('date')->limit(1)])
    ->get()
    ->filter(fn($patient) =>
        $patient->appointments->isNotEmpty() &&
        $patient->appointments->first()->date->addMonths(6)->isPast()
    );

    foreach ($patients as $patient) {
        Notification::create([
            'type' => 'patient_cleaning',
            'message' => "El paciente {$patient->first_name} {$patient->last_name} no ha tenido limpieza en más de 6 meses.",
            'scheduled_for' => now(),
        ]);
    }
    if (now()->day === 15) {
    Notification::create([
        'type' => 'bill_pending',
        'message' => "Recordatorio: Pagar facturas de luz y agua.",
        'scheduled_for' => now(),
    ]);
}

}

}
