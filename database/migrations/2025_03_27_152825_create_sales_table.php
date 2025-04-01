<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();  // 自動増分のID
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // 外部キー（productsテーブルのID）
            $table->timestamps();  // 作成日時と更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
