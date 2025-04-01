<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // 5件のダミーデータを挿入
        for ($i = 0; $i < 5; $i++) {
            DB::table('companies')->insert([
                'company_name' => $faker->company,
                'street_address' => $faker->address,
                'representative_name' => $faker->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
