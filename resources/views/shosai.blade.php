@extends('layouts.user')

@section('title', '商品情報編集画面')

@section('content')
<h1>商品情報編集画面</h1>
<div class="outside">
<form action="{{ route('update', ['id' => $products->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
        
        <!-- ID 行の修正 -->
        <tr>
            <td>ID</td>
            <td>{{$products->id}}</td>
        </tr>

        <!-- 商品名入力フィールド -->
        <p>商品名<a class="red">*</a></p>
        <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $products->product_name) }}">
        @error('product_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!-- メーカー名選択肢 -->
        <p>メーカー名<a class="red">*</a></p>
        <select name="company_name">
            <option value="">選択してください</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ old('company_name', $products->company_id) == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        @error('company_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!-- 価格入力フィールド -->
        <p>価格<a class="red">*</a></p>
        <input type="text" id="price" name="price" value="{{ old('price', $products->price) }}">
        @error('price')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!-- 在庫数入力フィールド -->
        <p>在庫数<a class="red">*</a></p>
        <input type="text" id="stock" name="stock" value="{{ old('stock', $products->stock) }}">
        @error('stock')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!-- コメント入力フィールド -->
        <p>コメント</p>
        <input type="text" id="comment" name="comment" value="{{ old('comment', $products->comment) }}">

        <!-- 画像アップロードフィールド -->
        <p>商品画像</p>
        <input type="file" id="img_path" name="img_path">

        
        <button type="submit" class="regist">更新</button>
        <button type="button" class="info" onClick="history.back()">戻る</button>
    </form>
    
</div>
@endsection


