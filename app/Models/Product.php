<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\Sale;

class Product extends Model
{
    protected $fillable = ['product_name','price','stock','comment','image_path','company_name'];

    public function getList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();
        return $products;
    }

    public function registProduct($data,$image_path) {
        // 登録処理
    
        DB::table('products')->insert([

            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'image_path' => $image_path,
            'company_id' => $data->company_id
        ]);


    }

    //リレーション
    public function company() {

        return $this->belongsTo(Company::class);

    }



    // 更新処理
    public function update_date($data, $products, $image_path){

        $products= $products->fill([
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'image_path' => $image_path,
            'company_id' => $data->company_id
        ])->save();

    }

}
