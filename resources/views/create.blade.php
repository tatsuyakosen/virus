@extends('layouts.user') 
@section('title', '商品新規登録画面') 
@section('content')

<h1 class="page-title">商品新規登録画面</h1>

<div class="form-container">
    <!-- action属性がregistSubmitという名前のルートに設定されています。これは、フォームが送信されたときに呼び出されるコントローラメソッドを指す -->
    <form action="{{ route('updateProduct') }}" method="post" enctype="multipart/form-data" name="img_path" class="product-form">
        @csrf

        <div class="form-group">
            <label for="product_name">商品名<span class="red">*</span></label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" class="form-control" />
            @if($errors->has('product_name'))
                <p class="error-message">{{ $errors->first('product_name') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label for="company_id">メーカー名<span class="red">*</span></label>
            <select name="company_id" class="form-control">
                <option value="">メーカー名を選択</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="price">価格<span class="red">*</span></label>
            <input type="text" id="price" name="price" value="{{ old('price') }}" class="form-control" />
            @if($errors->has('price'))
                <p class="error-message">{{ $errors->first('price') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label for="stock">在庫数<span class="red">*</span></label>
            <input type="text" id="stock" name="stock" value="{{ old('stock') }}" class="form-control" />
            @if($errors->has('stock'))
                <p class="error-message">{{ $errors->first('stock') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea id="comment" name="comment" class="form-control">{{ old('comment') }}</textarea>
        </div>

        <div class="form-group">
            <label for="img_path">商品画像</label>
            <input type="file" id="img_path" name="img_path" class="form-control" />
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">新規登録</button>
            <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('list') }}'">戻る</button>
        </div>
    </form>
</div>

<style>
    .page-title {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }
    .form-container {
        width: 60%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
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
        box-sizing: border-box;
    }
    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }
    .form-actions {
        text-align: center;
        margin-top: 20px;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn + .btn {
        margin-left: 10px;
    }
</style>

@endsection
