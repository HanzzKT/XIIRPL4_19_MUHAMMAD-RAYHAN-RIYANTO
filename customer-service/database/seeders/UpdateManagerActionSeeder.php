<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;

class UpdateManagerActionSeeder extends Seeder
{
    public function run()
    {
        // Update complaints yang sudah ada action_notes tapi belum ada manager_action
        $complaints = Complaint::whereNotNull('action_notes')
            ->where('action_notes', 'like', 'Manager Action:%')
            ->whereNull('manager_action')
            ->get();

        foreach ($complaints as $complaint) {
            if (str_contains($complaint->action_notes, 'Manager Action: resolved')) {
                $complaint->update([
                    'manager_action' => 'resolved',
                    'manager_action_at' => $complaint->updated_at,
                    'manager_action_by' => 2, // Assuming Manager ID is 2
                ]);
            } elseif (str_contains($complaint->action_notes, 'Manager Action: return_to_cs')) {
                $complaint->update([
                    'manager_action' => 'return_to_cs',
                    'manager_action_at' => $complaint->updated_at,
                    'manager_action_by' => 2, // Assuming Manager ID is 2
                ]);
            }
        }

        $this->command->info('Updated ' . $complaints->count() . ' complaints with manager_action field.');
    }
}
