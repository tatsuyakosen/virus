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
    public function showList(Request $request)
{
    $sort_column = $request->get('sort_column', 'id'); // デフォルトは'id'
    $sort_direction = $request->get('sort_direction', 'desc'); // デフォルトは降順

    // company_name を含めたソートに対応するために JOIN を使用
    $products = DB::table('products')
        ->join('companies', 'products.company_id', '=', 'companies.id')
        ->select('products.*', 'companies.company_name')
        ->orderBy($sort_column === 'company_name' ? 'companies.company_name' : "products.$sort_column", $sort_direction)
        ->get();

    $companies = Company::all();

    return view('product', [
        'products' => $products,
        'companies' => $companies,
        'sort_column' => $sort_column,
        'sort_direction' => $sort_direction,
    ]);
}

         


// 検索
public function search(Request $request)
{
    $validatedData = $request->validate([
        'keyword' => 'nullable|string|max:255',
        'company_id' => 'nullable|integer',
        'price_min' => 'nullable|numeric',
        'price_max' => 'nullable|numeric',
        'stock_min' => 'nullable|integer',
        'stock_max' => 'nullable|integer',
    ]);

    $keyword = $validatedData['keyword'] ?? null;
    $company_id = $validatedData['company_id'] ?? null;
    $price_min = $validatedData['price_min'] ?? null;
    $price_max = $validatedData['price_max'] ?? null;
    $stock_min = $validatedData['stock_min'] ?? null;
    $stock_max = $validatedData['stock_max'] ?? null;

    // リレーションを使った検索クエリ
    $products = Product::with('company')  
        ->when($keyword, function($query, $keyword) {
            return $query->where('product_name', 'LIKE', "%{$keyword}%");
        })
        ->when($company_id, function($query, $company_id) {
            return $query->where('company_id', $company_id);
        })
        ->when($price_min, function($query, $price_min) {
            return $query->where('price', '>=', $price_min);
        })
        ->when($price_max, function($query, $price_max) {
            return $query->where('price', '<=', $price_max);
        })
        ->when($stock_min, function($query, $stock_min) {
            return $query->where('stock', '>=', $stock_min);
        })
        ->when($stock_max, function($query, $stock_max) {
            return $query->where('stock', '<=', $stock_max);
        })
        ->get();

    $companies = Company::all();

    return view('product', [
        'products' => $products,
        'companies' => $companies,
        'company_id' => $company_id,
        'keyword' => $keyword,
        'price_min' => $price_min,
        'price_max' => $price_max,
        'stock_min' => $stock_min,
        'stock_max' => $stock_max,
    ]);
}


    //product から info(詳細)へ
    public function info() {
        $model = new Company();
        $companies = $model->getList_companies();
        return view('info/{id}',['companies' => $companies]);
    }



    // 削除
    public function delete(Request $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            $product->delete();
            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete the product.'], 500);
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
        $products = Product::with('company')->find($id); 
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