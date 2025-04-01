<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  // 自動増分のID
            $table->foreignId('company_id')->constrained()->onDelete('cascade');  // 外部キー（companiesテーブルのID）
            $table->string('product_name');  // 商品名
            $table->integer('price');  // 価格
            $table->integer('stock');  // 在庫数
            $table->text('comment')->nullable();  // コメント（任意）
            $table->string('img_path')->nullable();  // 商品画像のパス（任意）
            $table->timestamps();  // 作成日時と更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
