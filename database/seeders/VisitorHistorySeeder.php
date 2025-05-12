<?php

namespace Database\Seeders;

use App\Models\VisitorHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitorHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        VisitorHistory::create([
            'visitor_id' => 1,
            'visit_date' => '2025-03-22',
            'purpose' => 'Business Meeting',
            'status' => 'Completed',
        ]);

        VisitorHistory::create([
            'visitor_id' => 2,
            'visit_date' => '2025-03-23',
            'purpose' => 'Interview',
            'status' => 'Pending',
        ]);
    }
}
