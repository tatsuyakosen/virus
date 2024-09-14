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
    public function showList()
    {
        // Productモデルをインスタンス化し、データを取得
        $model = new Product();
        $products = $model->getList();
        $companies = Company::all(); 
        

        // 取得したデータをビューに渡す
        return view('product', [
            'products' => $products,
            'companies' => $companies,
        ]);
    }
         


// 検索
public function search(Request $request)
{
    $validatedData = $request->validate([
        'keyword' => 'nullable|string|max:255',
        'company_id' => 'nullable|integer',
    ]);

    $keyword = $validatedData['keyword'] ?? null;
    $company_id = $validatedData['company_id'] ?? null;

    // リレーションを使った検索クエリ
    $products = Product::with('company')  // companyリレーションをロード
        ->when($keyword, function($query, $keyword) {
            return $query->where('product_name', 'LIKE', "%{$keyword}%");
        })
        ->when($company_id, function($query, $company_id) {
            return $query->where('company_id', $company_id);
        })
        ->get();

    // メーカー一覧の取得
    $companies = Company::all();

    return view('product', [
        'products' => $products,
        'companies' => $companies,
        'company_id' => $company_id,
        'keyword' => $keyword,
    ]);
}


    //product から info(詳細)へ
    public function info() {
        $model = new Company();
        $companies = $model->getList_companies();
        return view('info/{id}',['companies' => $companies]);
    }



    //削除
    public function delete(Request $request, Product $product)
{
    try {
        $product = Product::findOrFail($request->id);
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // レコードが見つからなかった場合の処理
        return back()->with('error', 'Product not found.');
    } catch (\Exception $e) {
        // その他のエラーが発生した場合の処理
        return back()->with('error', 'Failed to delete the product.');
    }
}



    // product から  登録画面へ
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
            // 取得したファイル名で保存
            $request->file('img_path')->storeAs('public/' . $dir, $file_name);
            $img_path = 'storage/' . $dir . '/' . $file_name;
            
            // Eloquentモデルでの登録処理
            Product::create([
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
                'img_path' => $img_path,
                'company_id' => $request->input('company_id')
            ]);
    
            DB::commit();
            return redirect(route('list'))->with('success', '商品が登録されました');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => '登録に失敗しました'])->withInput();
        }
    }
    


    //商品情報編集画面
    public function showShosai() {

        $model = new Product();
        $products = $model->getList();
        $model = new Company();
        $companies = $model->getList_companies();
        $img_path = Product::all();
        $company =Company::all();

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
            'company_id' => $request->input('company_id'),
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