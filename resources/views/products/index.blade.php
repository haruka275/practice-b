@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">{{ __('商品情報一覧') }}</div>

                <div class="card-body">
                    <!-- 検索フォーム -->
                    <div class="d-flex justify-content-between mb-4">
                        <form id="search-form" class="form-inline w-100">
                            <div class="form-group mr-3 w-25">
                                <input type="text" class="form-control w-100" id="product_name" name="product_name" placeholder="商品名" value="{{ request()->get('product_name') }}">
                            </div>
                            <div class="form-group mr-3 w-25">
                                <select name="manufacturer" id="manufacturer" class="form-control w-100">
                                    <option value="">メーカー名</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->name }}" {{ request()->get('manufacturer') == $company->name ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- 価格の絞り込み -->
                            <div class="form-group mr-3 w-25">
                                <input type="number" class="form-control w-100" id="price_min" name="price_min" placeholder="価格 (最小)" value="{{ request()->get('price_min') }}">
                            </div>
                            <div class="form-group mr-3 w-25">
                                <input type="number" class="form-control w-100" id="price_max" name="price_max" placeholder="価格 (最大)" value="{{ request()->get('price_max') }}">
                            </div>
                            <!-- 在庫数の絞り込み -->
                            <div class="form-group mr-3 w-25">
                                <input type="number" class="form-control w-100" id="stock_min" name="stock_min" placeholder="在庫数 (最小)" value="{{ request()->get('stock_min') }}">
                            </div>
                            <div class="form-group mr-3 w-25">
                                <input type="number" class="form-control w-100" id="stock_max" name="stock_max" placeholder="在庫数 (最大)" value="{{ request()->get('stock_max') }}">
                            </div>
                            <button type="button" id="search-btn" class="btn btn-info">検索</button>
                        </form>
                        <!-- 新規登録ボタン -->
                        <a href="{{ route('products.create') }}" class="btn btn-warning">新規登録</a>
                    </div>

                    <!-- 商品一覧テーブル -->
                    <table class="table table-bordered" id="product-table">
                        <thead>
                            <tr>
                                <th><a href="{{ route('products.index', ['sort_column' => 'id', 'sort_direction' => $sortColumn === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">商品ID</a></th>
                                <th>商品画像</th>
                                <th><a href="{{ route('products.index', ['sort_column' => 'product_name', 'sort_direction' => $sortColumn === 'product_name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">商品名</a></th>
                                <th><a href="{{ route('products.index', ['sort_column' => 'price', 'sort_direction' => $sortColumn === 'price' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">価格</a></th>
                                <th><a href="{{ route('products.index', ['sort_column' => 'stock', 'sort_direction' => $sortColumn === 'stock' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">在庫数</a></th>
                                <th><a href="{{ route('products.index', ['sort_column' => 'company_id', 'sort_direction' => $sortColumn === 'company_id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">メーカー名</a></th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            @foreach($products as $product)
                            <tr id="product-{{ $product->id }}">
                                <td>{{ $product->id }}</td>
                                <td><img src="{{ asset('storage/'.$product->img_path) }}" alt="商品画像" width="50"></td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->price }}円</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->company ? $product->company->name : '未登録' }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細表示</a>
                                    <!-- 削除フォーム -->
                                    <button type="button" class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->product_name }}">削除</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- ページネーション -->
                    <div class="pagination-wrapper">
                        <div class="pagination pagination-sm">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // 検索ボタンの非同期処理
        $('#search-btn').on('click', function() {
            var productName = $('#product_name').val();
            var manufacturer = $('#manufacturer').val();
            var priceMin = $('#price_min').val();
            var priceMax = $('#price_max').val();
            var stockMin = $('#stock_min').val();
            var stockMax = $('#stock_max').val();

            $.ajax({
                url: "{{ route('products.index') }}",
                method: 'GET',
                data: {
                    product_name: productName,
                    manufacturer: manufacturer,
                    price_min: priceMin,
                    price_max: priceMax,
                    stock_min: stockMin,
                    stock_max: stockMax
                },
                success: function(response) {
                    // 商品一覧の更新
                    $('#product-list').html(response.products);
                    // ページネーションの更新
                    $('.pagination-wrapper').html(response.pagination);
                }
            });
        });

        // 削除ボタンの非同期処理
        $(document).on('click', '.delete-btn', function() {
            var productId = $(this).data('product-id');
            var productName = $(this).data('product-name');

            if (confirm("本当に「" + productName + "」を削除しますか？")) {
                $.ajax({
                    url: "/products/" + productId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            // 商品が削除されたらその行を非表示にする
                            $('#product-' + productId).fadeOut();
                        } else {
                            alert("削除に失敗しました。再度お試しください。");
                        }
                    },
                    error: function() {
                        alert("削除に失敗しました。もう一度試してください。");
                    }
                });
            }
        });
    });
</script>

@endsection
