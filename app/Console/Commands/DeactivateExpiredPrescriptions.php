<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PrescriptionDetail;
use Carbon\Carbon;

class DeactivateExpiredPrescriptions extends Command
{
    protected $signature = 'prescriptions:deactivate-expired';
    protected $description = 'Desactiva automáticamente los detalles de recetas vencidos';

    public function handle()
    {
        $now = Carbon::now();

        $expired = PrescriptionDetail::where('active', true)
            ->where('ending_date', '<', $now)
            ->update(['active' => false]);

        $this->info("Se desactivaron {$expired} detalles de recetas vencidos.");
    }
}
