<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Company;


class ProductController extends Controller
{
    // 表示
    public function showList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();

        return view('product', ['products' => $products]);
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
    public function touroku(ProductRequest $request) {
        // ディレクトリ名
        $dir = 'images';
        // アップロードされたファイル名を取得
        $file_name = $request->file('image_path')->getClientOriginalName();
        // トランザクション開始
        DB::beginTransaction();
        try {
        // 登録処理呼び出し
        $model = new Product();
            // 取得したファイル名で保存
        $request->file('image_path')->storeAs('public/' . $dir, $file_name);
        $image_path = 'storage/' . $dir . '/' . $file_name;
        $model->registProduct($request,$image_path);
        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
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
        $image_path = Product::all();
        $company =Company::all();
        $products = $model->getList();

    return view('shosai', ['company' => $company,'companies' => $companies, 'products' => $products, 'image_path' =>$image_path]);
    }


    public function shosai(Request $request) {
        $id = $request->id;
        $products = Product::find($id);
        $model = new Company();
        $companies = $model->getList_companies();
        return view('shosai',['products' => $products,'companies' => $companies]);
    }



     //更新
     public function update(Request $request, $id)
{
    DB::beginTransaction();
    try {
        $products = Product::findOrFail($id);

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $request->file('image_path')->storeAs('public/images', $fileName);
            $products->update([
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'company_id' => $request->input('company_name'),
                'image_path' => 'storage/images/' . $fileName
            ]);
        } else {
            $products->update([
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'company_id' => $request->input('company_name')
            ]);
        }

        DB::commit();
        return redirect()->route('list');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withInput()->withErrors(['error' => '更新に失敗しました']);
    }
}

 }