@extends('layouts.user') @section('title', '商品新規登録画面') @section('content')

<h1>商品新規登録画面</h1>

<div class="outside">
　　　　<!-- action属性がregistSubmitという名前のルートに設定されています。これは、フォームが送信されたときに呼び出されるコントローラメソッドを指す -->
<form action="{{ route('touroku')}}" method="post" enctype="multipart/form-data" name="img_path">
    @csrf

    <p>商品名<a class="red">*</a></p>
    <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" />
    @if($errors->has('product_name'))
    <p>{{ $errors->first('product_name') }}</p>
    @endif




    <p>メーカー名<a class="red">*</a></p>
<select name="company_id" placeholder="メーカー名" >
    <option value="">メーカー名</option>
    @foreach($companies as $company)
    <option value="{{$company->id}}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
        {{$company->company_name}}
    </option>
    @endforeach
</select>


          <p>価格<a class="red">*</a></p>
    <input type="text" id="price" name="price" value="{{ old('price') }}" />
    @if($errors->has('price'))
    <p>{{ $errors->first('price') }}</p>
    @endif

    <p>在庫数<a class="red">*</a></p>
    <input type="text" id="stock" name="stock" value="{{ old('stock') }}" />
    @if($errors->has('stock'))
    <p>{{ $errors->first('stock') }}</p>
    @endif

    <p>コメント</p>
    <input type="text" id="comment" name="comment" value="{{ old('comment') }}" />

    <p>商品画像</p>
    <input type="file" id="img_path" name="img_path" value="{{ old('img_path') }}" />

    <div>

    <!--新規登録ボタンでactionが発動-->
        <button type="submit" class="regist">新規登録</button>

        <button type="button" class="info" onclick="location.href='{{ route('list') }}'">戻る</button>
    </div>
@endsection
</form>



</div>