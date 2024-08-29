<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\Sale;

class Product extends Model
{
    protected $fillable = ['product_name','price','stock','comment','img_path','company_name'];

    public function getList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();
        return $products;
    }

    public function registProduct($data,$img_path) {
        // 登録処理
    
        DB::table('products')->insert([

            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $img_path,
            'company_id' => $data->company_name
        ]);


    }

    //リレーション
    public function company() {

        return $this->belongsTo(Company::class);

    }



    // 更新処理
    public function update_date($data, $products, $img_path){

        $products= $products->fill([
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $img_path,
            'company_id' => $data->company_name
        ])->save();

    }

}
