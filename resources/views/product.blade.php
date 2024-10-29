@extends('layouts.user')
@section('title', '商品一覧画面')
@section('content')

<h1 class="page-title">商品一覧画面</h1>

<div class="form-container">
    <form action="{{ route('list') }}" method="GET" id="product_search" class="search-form">
        @csrf
        <div class="form-group">
            <input type="text" id="keyword" name="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}" class="form-control" />
        </div>

        <div class="form-group">
            <select name="company_id" class="form-control">
                <option value="">メーカー名を選択</option>
                @if(!empty($companies))
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group">
            <input type="number" name="price_min" placeholder="価格下限" value="{{ request('price_min') }}" class="form-control" />
            <input type="number" name="price_max" placeholder="価格上限" value="{{ request('price_max') }}" class="form-control" />
        </div>

        <div class="form-group">
            <input type="number" name="stock_min" placeholder="在庫下限" value="{{ request('stock_min') }}" class="form-control" />
            <input type="number" name="stock_max" placeholder="在庫上限" value="{{ request('stock_max') }}" class="form-control" />
        </div>

        <div class="form-actions">
            <input type="submit" class="btn btn-primary" id="search" value="検索" />
        </div>
    </form>
</div>

<div id="product_table" class="table-container">
    <table id="myTable" class="tablesorter">
        <thead>
            <tr>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">ID</a></th>
                <th>商品画像</th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'product_name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">商品名</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'price', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">価格</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'stock', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">在庫数</a></th>
                <th><a href="{{ route('list', array_merge(request()->all(), ['sort_column' => 'company_name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">メーカー名</a></th>
                <th><button type="button" class="btn btn-secondary" onclick="location.href='{{ route('new') }}'">新規登録</button></th>
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
                    <a href="{{ route('show', ['id'=>$product->id]) }}" class="btn btn-info">詳細</a>
                </td>

                <!-- 削除 -->
                <td>
                    <form action="{{ route('delete', ['id'=>$product->id]) }}" method="post" class="delete-form">
                        @csrf
                        <button type="submit" class="btn btn-danger delete" data-product_id="{{ $product->id }}">削除</button>
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

        // 削除の非同期処理（イベントデリゲーションを使用）
        $(document).on('click', '.delete', function(e) {
            e.preventDefault(); // フォームの通常送信を防ぐ
            var productId = $(this).data('product_id');
            var form = $(this).closest('form'); // フォームを取得

            if (confirm('本当に削除しますか？')) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
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

<style>
    .page-title {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }
    .form-container {
        width: 80%;
        margin: 0 auto 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    .search-form .form-group {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    .search-form .form-group .form-control {
        flex: 1 1 200px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .search-form .form-actions {
        text-align: center;
    }
    .table-container {
        width: 90%;
        margin: 0 auto;
    }
    .tablesorter {
        width: 100%;
        border-collapse: collapse;
    }
    .tablesorter th, .tablesorter td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .tablesorter th {
        background-color: #f2f2f2;
    }
    .btn {
        padding: 6px 12px;
        margin: 2px;
        font-size: 14px;
        cursor: pointer;
    }
    .btn-primary { background-color: #007bff; color: #fff; border: none; }
    .btn-secondary { background-color: #6c757d; color: #fff; border: none; }
    .btn-info { background-color: #17a2b8; color: #fff; border: none; }
    .btn-danger { background-color: #dc3545; color: #fff; border: none; }
</style>

@endsection
