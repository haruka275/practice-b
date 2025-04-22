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
                        <form id="search-form" class="form-inline w-100" method="GET" action="{{ route('products.index') }}">
                            <div class="form-group mr-3 w-25">
                                <input type="text" class="form-control w-100" id="product_name" name="product_name" placeholder="商品名" value="{{ request()->get('product_name') }}">
                            </div>
                            <div class="form-group mr-3 w-25">
                                <select name="manufacturer" id="manufacturer" class="form-control w-100">
                                    <option value="">メーカー名</option>
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request()->get('manufacturer') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
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
                            <button type="submit" class="btn btn-info" id="search-btn">検索</button>
                        </form>
                        <!-- 新規登録ボタン -->
                        <a href="{{ route('products.create') }}" class="btn btn-warning">新規登録</a>
                    </div>

                    <!-- 商品一覧テーブル -->
                    <table class="table table-bordered" id="product-table">
                        <thead>
                            <tr>
                                @php
                                    $queryParams = request()->except('sort_column', 'sort_direction');
                                @endphp
                                <th><a href="{{ route('products.index', array_merge($queryParams, ['sort_column' => 'id', 'sort_direction' => $sortColumn === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}">商品ID</a></th>
                                <th>商品画像</th>
                                <th><a href="{{ route('products.index', array_merge($queryParams, ['sort_column' => 'product_name', 'sort_direction' => $sortColumn === 'product_name' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}">商品名</a></th>
                                <th><a href="{{ route('products.index', array_merge($queryParams, ['sort_column' => 'price', 'sort_direction' => $sortColumn === 'price' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}">価格</a></th>
                                <th><a href="{{ route('products.index', array_merge($queryParams, ['sort_column' => 'stock', 'sort_direction' => $sortColumn === 'stock' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}">在庫数</a></th>
                                <th><a href="{{ route('products.index', array_merge($queryParams, ['sort_column' => 'company_id', 'sort_direction' => $sortColumn === 'company_id' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}">メーカー名</a></th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            @include('products.partials.product_list')
                        </tbody>
                    </table>

                    <!-- ページネーション -->
                    <div class="pagination-wrapper">
                        <div class="pagination pagination-sm">
                        {!! $products->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
    $(document).ready(function () {
        // 共通のAjax取得処理
        function fetchProducts(params) {
            $.ajax({
                url: "{{ route('products.index') }}",
                method: 'GET',
                data: params,
                success: function (response) {
                    console.log("★取得したページネーションHTML:", response.pagination);
                    $('#product-list').html(response.products);
                    $('.pagination-wrapper').html(response.pagination);
                },
                error: function () {
                    alert("通信エラーが発生しました。");
                }
            });
        }

        // 検索フォーム送信時
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            const params = $(this).serialize();
            fetchProducts(params);
        });

        // ページネーションリンククリック時
        $(document).on('click', '.pagination a', function (e) {
            console.log("✅ ページネーションリンクがクリックされた");
            e.preventDefault();
            const pageUrl = $(this).attr('href');
            const url = new URL(pageUrl, window.location.origin);
            const page = url.searchParams.get('page');

            const formParams = $('#search-form').serializeArray();
            formParams.push({ name: 'page', value: page });

            fetchProducts($.param(formParams));
        });

        // 並び替えリンククリック時
        $(document).on('click', 'th a', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const url = new URL(href, window.location.origin);

            const sortColumn = url.searchParams.get('sort_column');
            const sortDirection = url.searchParams.get('sort_direction');

            const formParams = $('#search-form').serializeArray();
            formParams.push({ name: 'sort_column', value: sortColumn });
            formParams.push({ name: 'sort_direction', value: sortDirection });

            fetchProducts($.param(formParams));
        });

        // 商品削除処理（非同期）
        $(document).on('click', '.delete-btn', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');

            if (confirm("本当に「" + productName + "」を削除しますか？")) {
                $.ajax({
                    url: "/products/" + productId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#product-' + productId).fadeOut();
                        } else {
                            alert("削除に失敗しました。再度お試しください。");
                        }
                    },
                    error: function () {
                        alert("削除に失敗しました。もう一度試してください。");
                    }
                });
            }
        });
    });
</script>
@endsection
