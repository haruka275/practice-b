<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;  // Productモデルを使う

class ProductSeeder extends Seeder
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
            Product::firstOrCreate(
                [
                    'product_name' => $faker->word,  // 商品名が既に存在するかをチェック
                    'company_id' => rand(1, 5)  // 会社IDが既に存在するかをチェック
                ],
                [
                    'price' => rand(100, 1000),  // ランダムな価格
                    'stock' => rand(1, 100),  // ランダムな在庫数
                    'comment' => $faker->sentence,  // ダミーのコメント
                    'img_path' => 'images/' . $faker->word . '.jpg',  // ダミーの画像パス
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
