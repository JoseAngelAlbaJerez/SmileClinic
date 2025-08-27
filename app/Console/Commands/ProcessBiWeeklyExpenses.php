<?php

namespace App\Console\Commands;

use App\Models\Expenses;
use App\Models\PendingExpense;
use Illuminate\Console\Command;

class ProcessBiWeeklyExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenses:process-biweekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move pending expenses into actual expenses on quota days (15th & end of month)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->day;
        $lastDay = now()->endOfMonth()->day;
        if (!in_array($today, [15, $lastDay])) {
            $this->info('Not a quota day. Skipping.');
            return;
        }

        $pending = PendingExpense::all();
        $this->info($pending);

        foreach ($pending as $expense) {
            Expenses::create([
                'description' => $expense->description,
                'amount'      => $expense->amount,
                'active'      => true,
                'user_id'     => $expense->user_id,
                'branch_id'   => $expense->branch_id,
            ]);

            $expense->delete();
        }

        $this->info('Bi-weekly pending expenses processed.');

    }
}
