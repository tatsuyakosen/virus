@extends('layouts.user')

@section('title', '商品情報編集画面')

@section('content')
<h1 class="page-title">商品情報編集画面</h1>
<div class="form-container">
    <form action="{{ route('update', ['id' => $products->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <!-- ID 表示 -->
        <div class="form-group">
            <label for="product_id">ID</label>
            <p class="form-control-static">{{ $products->id }}</p>
        </div>

        <!-- 商品名入力フィールド -->
        <div class="form-group">
            <label for="product_name">商品名 <span class="red">*</span></label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $products->product_name) }}" class="form-control">
            @error('product_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- メーカー名選択肢 -->
        <div class="form-group">
            <label for="company_id">メーカー名 <span class="red">*</span></label>
            <select name="company_id" class="form-control">
                <option value="">選択してください</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $products->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- 価格入力フィールド -->
        <div class="form-group">
            <label for="price">価格 <span class="red">*</span></label>
            <input type="text" id="price" name="price" value="{{ old('price', $products->price) }}" class="form-control">
            @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- 在庫数入力フィールド -->
        <div class="form-group">
            <label for="stock">在庫数 <span class="red">*</span></label>
            <input type="text" id="stock" name="stock" value="{{ old('stock', $products->stock) }}" class="form-control">
            @error('stock')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- コメント入力フィールド -->
        <div class="form-group">
            <label for="comment">コメント</label>
            <input type="text" id="comment" name="comment" value="{{ old('comment', $products->comment) }}" class="form-control">
        </div>

        <!-- 画像アップロードフィールド -->
        <div class="form-group">
            <label for="img_path">商品画像</label>
            <input type="file" id="img_path" name="img_path" class="form-control">
        </div>

        <!-- ボタン -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新</button>
            <button type="button" class="btn btn-secondary" onClick="history.back()">戻る</button>
        </div>
    </form>
</div>

<style>
    .page-title {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
    }
    .form-container {
        width: 60%;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #ccc;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .form-control-static {
        padding: 8px 0;
        font-size: 16px;
    }
    .invalid-feedback {
        color: red;
        font-size: 12px;
    }
    .form-actions {
        text-align: center;
        margin-top: 20px;
    }
    .btn {
        padding: 8px 15px;
        margin: 5px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    .red {
        color: red;
    }
</style>

@endsection
