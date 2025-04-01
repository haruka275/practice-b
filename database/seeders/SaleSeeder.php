<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // 10件のダミーデータを挿入
        for ($i = 0; $i < 10; $i++) {
            DB::table('sales')->insert([
                'product_id' => rand(1, 10), // 仮の製品ID
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
