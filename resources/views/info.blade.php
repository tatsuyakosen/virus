@extends('layouts.user') @section('title', '商品詳細画面') @section('content')
    <table class="table" style="width: 1000px; max-width: 0 auto;">
    <div class="outside">
    <h1>商品情報詳細画面</h1>

    @foreach($products as $product)
    @if($loop->first)
        <tr>
            <td>ID</td>
            <td>{{$products->id}}</td>
        </tr>

        <tr>
            <td>商品画像</td>
            <td><img src="{{asset($products->img_path)}}" width="50" height="50"></td>
        </tr>

        <tr>
            <td>商品名</td>
            <td>{{$products->product_name}}</td>
        </tr>

        <tr>
            <td>メーカー名</td>
            <td> {{$products->company->company_name}} </td>
        </tr>

        <tr>
            <td>価格</td>
            <td>{{$products->price}}</td>
        </tr>

        <tr>
            <td>在庫数</td>
            <td>{{$products->stock}}</td>
        </tr>

        <tr>
            <td>コメント</td>
            <td>{{$products->comment}}</td>
        </tr>
        </table>

        <button class="regist" onclick="location.href='{{ route('showDetail', ['id' => $products->id]) }}' ">編集</button>

        <button class="info" onclick="location.href='{{ route('list') }}' ">戻る</button>
    @endif @endforeach
    </div>
    @endsection






