<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\Sale;

class Product extends Model
{
    protected $fillable = ['product_name','price','stock','comment','img_path','company_id'];

    public function getList() {
        // productsテーブルとcompaniesテーブルを結合して全てのデータを取得
        $products = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name') // 必要に応じてカラムを選択
            ->get();

        $companies = Company::all();
                
            return $products; // productsデータを返す
    }

    public function registProduct($data,$img_path) {
        // 登録処理
    
        DB::table('products')->insert([

            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $img_path,
            'company_id' => $data->company_id
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
