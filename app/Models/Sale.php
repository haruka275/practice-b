<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // 購入情報の保存を許可するフィールドを指定
    protected $fillable = ['product_id', 'quantity', 'purchased_at'];

    // リレーション：Productに属する (Saleは1つのProductに属する)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
