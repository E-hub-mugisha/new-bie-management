<?php

namespace Database\Seeders;

use App\Models\VisitorHistory;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purposes = ['Meeting', 'Delivery', 'Interview', 'Maintenance', 'Tour'];
        $statuses = ['Checked In', 'Checked Out'];


        // Get all existing visitor IDs
        $visitorIds = DB::table('visitors')->pluck('id')->toArray();

        if (empty($visitorIds)) {
            $this->command->warn('No visitors found. Run VisitorSeeder first.');
            return;
        }
        // Assuming you already have 10 visitors with IDs 1 to 10
        foreach (range(1, 10) as $visitorId) {
            $checkIn = Carbon::now()->subDays(rand(1, 30))->setTime(rand(8, 11), rand(0, 59));
            $checkOut = (clone $checkIn)->addHours(rand(1, 4));

            DB::table('visitor_histories')->insert([
                'visitor_id' => $visitorId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'purpose' => $purposes[array_rand($purposes)],
                'status' => 'Checked Out',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
