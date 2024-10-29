@extends('layouts.user') 
@section('title', '商品詳細画面') 
@section('content')

<div class="detail-container">
    <h1 class="page-title">商品情報詳細画面</h1>
    <table class="detail-table">
        <tbody>
            @foreach($products as $product)
                @if($loop->first)
                    <tr>
                        <th>ID</th>
                        <td class="text-center">{{$products->id}}</td>
                    </tr>
                    <tr>
                        <th>商品画像</th>
                        <td class="text-center"><img src="{{asset($products->img_path)}}" class="product-image"></td>
                    </tr>
                    <tr>
                        <th>商品名</th>
                        <td class="text-center">{{$products->product_name}}</td>
                    </tr>
                    <tr>
                        <th>メーカー名</th>
                        <td class="text-center">{{$products->company->company_name}}</td>
                    </tr>
                    <tr>
                        <th>価格</th>
                        <td class="text-center">{{$products->price}}</td>
                    </tr>
                    <tr>
                        <th>在庫数</th>
                        <td class="text-center">{{$products->stock}}</td>
                    </tr>
                    <tr>
                        <th>コメント</th>
                        <td class="text-center">{{$products->comment}}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="form-actions">
        <button class="btn btn-secondary" onclick="location.href='{{ route('showDetail', ['id' => $products->id]) }}'">編集</button>
        <button class="btn btn-primary" onclick="location.href='{{ route('list') }}'">戻る</button>
    </div>
</div>

<style>
    .detail-container {
        width: 60%;
        margin: 0 auto;
        padding: 20px;
    }
    .page-title {
        text-align: center;
        margin-bottom: 30px;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .detail-table th, .detail-table td {
        padding: 10px;
        border: 1px solid #ccc;
        vertical-align: middle; 
    }
    .detail-table th {
        background-color: #f2f2f2;
        text-align: center; 
        width: 30%;
    }
    .text-center {
        text-align: center; 
    }
    .product-image {
        max-width: 200px;
        height: auto;
    }
    .form-actions {
        text-align: center;
    }
    .btn {
        padding: 8px 15px;
        margin: 5px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        border: none;
    }
</style>

@endsection
