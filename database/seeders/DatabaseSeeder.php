<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('product_statuses')->insert([
            'title' => 'ACTIVE',
        ]);
        DB::table('product_statuses')->insert([
            'title' => 'PENDING',
        ]);
        DB::table('product_statuses')->insert([
            'title' => 'INACTIVE',
        ]);
        DB::table('order_statuses')->insert([
            'title' => 'PENDING',
        ]);
        DB::table('order_statuses')->insert([
            'title' => 'IN PROCESS',
        ]);
        DB::table('order_statuses')->insert([
            'title' => 'DELIVERED',
        ]);
        DB::table('order_statuses')->insert([
            'title' => 'CANCELED',
        ]);
    }
}
