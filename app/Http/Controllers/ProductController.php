<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\TourokuRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Company;


class ProductController extends Controller
{
    // 表示
    public function showList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();
        $model = new Company();
        $companies = $model->getList_companies();

        return view('product', ['products' => $products, 'companies' => $companies]);
    }
         


// 検索
public function search(Request $request)
{
    $validatedData = $request->validate([
        'keyword' => 'nullable|string|max:255',
        'company_id' => 'nullable|integer',
    ]);

    $keyword = $validatedData['keyword'];
    $company_id = $validatedData['company_id'];

    $query = Product::query();
    if ($keyword) {
        $query->where('product_name', 'LIKE', "%{$keyword}%");
    }

    if ($company_id) {
        $query->where('company_id', "=", $company_id);
    }

    $products = $query->paginate(3);

    $companies = Company::all();

    return view('product', [
        'products' => $products,
        'companies' => $companies,
        'company_id' => old('company_id'),
        'keyword' => old('keyword'),
    ]);
}


    //product から info_product へ 詳細
    public function info() {
        $model = new Company();
        $companies = $model->getList_companies();
        return view('info/{id}',['companies' => $companies]);
    }



    //削除
    public function delete(Request $request, Product $product){
        $product = Product::findOrFail($request->id);
        $product->delete();
        return back();
    }



    // product から regist_product へ 登録
    public function new() {
        $model = new Product();
        $products = $model->getList();
        $model = new Company();
        $companies = $model->getList_companies();

        return view('shintouroku',['products' => $products, 'companies' => $companies]);
    }


    //詳細
    // 表示
    public function show(Request $request) {
        $id = $request->id;
        $products = Product::find($id);
        $model = new Company();
        $companies = $model->getList_companies();
        $company_name = $request->company_name;
        $companies = Company::find($company_name);

        return view('info',['products' => $products,'companies' => $companies]);
    }



    // 新規登録
    public function touroku(TourokuRequest $request) {
        // ディレクトリ名
        $dir = 'img';
        // アップロードされたファイル名を取得
        $file_name = $request->file('img_path')->getClientOriginalName();
        // トランザクション開始
        DB::beginTransaction();
        try {
        // 登録処理呼び出し
        $model = new Product();
            // 取得したファイル名で保存
        $request->file('img_path')->storeAs('public/' . $dir, $file_name);
        $img_path = 'storage/' . $dir . '/' . $file_name;
        $model->registProduct($request,$img_path);
        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->route('list');
    }

    
    return redirect(route('new'));
    }


    // regist_productから productへ
    public function back_product() {
        return view('product');
    }


    //商品情報編集画面
    public function showShosai() {

        $model = new Product();
        $products = $model->getList();
        $model = new Company();
        $companies = $model->getList_companies();
        $img_path = Product::all();
        $company =Company::all();
        $products = $model->getList();

    return view('shosai', ['company' => $company,'companies' => $companies, 'products' => $products, 'img_path' =>$img_path]);
    }


    public function shosai(Request $request) {
        $id = $request->id;
        $products = Product::find($id);
        $model = new Company();
        $companies = $model->getList_companies();
        return view('shosai',['products' => $products,'companies' => $companies]);
    }



     //更新
    
public function update(TourokuRequest $request, $id)
{
    DB::beginTransaction();
    try {
        $products = Product::findOrFail($id);

        if ($request->hasFile('img_path')) {
            $img = $request->file('img_path');
            $fileName = time() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('public/img', $fileName);
            $imgPath = 'storage/img/' . $fileName;
        } else {
            $imgPath = $products->img_path; // 画像がアップロードされなかった場合は、既存の画像パスを保持
        }

        $products->update([
            'product_name' => $request->input('product_name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'company_id' => $request->input('company_name'),
            'img_path' => $imgPath, // 常にimg_pathを更新
        ]);

        DB::commit();
        return redirect()->route('list');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withInput()->withErrors(['error' => '更新に失敗しました']);
    }
}

 }