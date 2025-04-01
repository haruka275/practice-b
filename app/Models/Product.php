<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'product_name', 'price', 'stock', 'comment', 'img_path'];

    // リレーション：Companyに属する (Productは1つのCompanyに属する)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // リレーション：Salesを持つ (Productは複数のSaleを持つ)
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
