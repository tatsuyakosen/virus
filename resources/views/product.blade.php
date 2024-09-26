@extends('layouts.user')
@section('title', '商品一覧画面')
@section('content')
<h1>商品一覧画面</h1>

<div class="outside">
<form action="{{ route('list') }}" method="GET" id="product_search">
    @csrf
    <input type="text" id="keyword" name="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}" />

    <select name="company_id">
        <option value="">メーカー名</option>
        @if(!empty($companies))
            @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->company_name }}
            </option>
            @endforeach
        @endif
    </select>

    <input type="number" name="price_min" placeholder="価格下限" value="{{ request('price_min') }}">
    <input type="number" name="price_max" placeholder="価格上限" value="{{ request('price_max') }}">

    <input type="number" name="stock_min" placeholder="在庫下限" value="{{ request('stock_min') }}">
    <input type="number" name="stock_max" placeholder="在庫上限" value="{{ request('stock_max') }}">

    <input type="submit" class="button" id="search" value="検索" />
</form>
</div>

<div id="product_table">
    <table id="myTable" class="tablesorter">
        <thead>
            <tr>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">ID</a></th>
                <th>商品画像</th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'product_name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">商品名</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'price', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">価格</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'stock', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">在庫数</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'company_name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">メーカー名</a></th>
                <th><button type="button" class="regist" onclick="location.href='{{ route('new') }}'">新規登録</button></th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr data-product_id="{{ $product->id }}">
                <td>{{ $product->id }}</td>
                <td><img src="{{ asset($product->img_path) }}" width="50" height="50"></td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->company->company_name ?? $product->company_name }}</td>

                <!-- 詳細 -->
                <td>
                    <form action="{{ route('show', ['id'=>$product->id]) }}">
                        @csrf
                        <button type="submit" class="info">詳細</button>
                    </form>
                </td>

                <!-- 削除 -->
                <td>
                    <form action="{{ route('delete', ['id'=>$product->id]) }}" method="post">
                        @csrf
                        <button type="submit" id="delete" class="delete" data-product_id="{{ $product->id }}">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // 検索の非同期処理
        $('#search').on('click', function(e) {
            e.preventDefault(); // フォームの通常送信を防ぐ
            var formData = $('#product_search').serialize(); // フォームデータを取得

            $.ajax({
                url: "{{ route('search') }}",
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#product_table').html($(response).find('#product_table').html());
                },
                error: function(xhr, status, error) {
                    console.log('検索に失敗しました: ' + error);
                }
            });
        });

        // 削除の非同期処理
        $('.delete').on('click', function(e) {
            e.preventDefault(); // フォームの通常送信を防ぐ
            var productId = $(this).data('product_id');
            var form = $(this).closest('form'); // フォームを取得

            if (confirm('本当に削除しますか？')) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // 成功時に削除対象の行を削除
                            $('tr[data-product_id="' + productId + '"]').remove();
                        } else {
                            alert('削除に失敗しました。');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('削除に失敗しました: ' + error);
                    }
                });
            }
        });
    });
</script>

@endsection
