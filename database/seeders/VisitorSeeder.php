<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $identificationTypes = ['National ID', 'Passport'];

        for ($i = 1; $i <= 10; $i++) {
            $now = Carbon::now();
            DB::table('visitors')->insert([
                'identification_number' => 'ID' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'identification_type' => $identificationTypes[array_rand($identificationTypes)],
                'user_id' => 1, // Assuming you have users with ID 1 to 5
                'name' => 'Visitor ' . $i,
                'email' => 'visitor' . $i . '@example.com',
                'phone' => '0788' . rand(100000, 999999),
                'address' => 'Address ' . $i,
                'created_at' => $now,
                'updated_at' => $now,
                'visitor_number' => 'V-' . strtoupper(Str::random(6)),
            ]);
        }
    }
}
