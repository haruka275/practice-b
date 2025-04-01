<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['company_name', 'street_address', 'representative_name'];

    // リレーション：1対多 (Companyは多くのProductを持つ)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
