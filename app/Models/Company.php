<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class Company extends Model
{
    public function getList_companies() {
        // productsテーブルからデータを取得
        $companies = DB::table('companies')->get();

        return $companies;
    }



    //リレーション
    public function product() {

        return $this->hasMany(Product::class);

    }


}