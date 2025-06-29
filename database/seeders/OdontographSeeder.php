<?php

namespace Database\Seeders;

use App\Models\Odontograph;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class OdontographSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::pluck('id');



    }
}
