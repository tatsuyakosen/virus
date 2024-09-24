<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    // 購入
    
    public function purchase(Request $request)
    {
        try {
            $productId = $request->product_id;
            $quantity = $request->quantity;
    
            $product = Product::find($productId);
            if (!$product || $product->stock < $quantity) {
                return response()->json(['error' => '在庫がありません']);
            }
    
            DB::transaction(function () use ($product, $quantity) {
                $product->decrement('stock', $quantity);
    
                Sale::create([
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
            });
    
            return response()->json(['message' => '購入完了']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during the transaction'], 500);
        }
    }
    
    
    }
    
    