<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function getList_sales() {
        // productsテーブルからデータを取得
        $sales = DB::table('sales')->get();

        return $sales;
     }



     public function Product() {

        return $this->belongsTo(Product::class);

    }


protected $fillable = ['product_id', 'quantity'];
}
