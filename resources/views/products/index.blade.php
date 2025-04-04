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
                        <form method="GET" action="{{ route('products.index') }}" class="form-inline w-100">
                            <div class="form-group mr-3 w-25">
                                <input type="text" class="form-control w-100" name="product_name" placeholder="検索キーワード" value="{{ request()->get('product_name') }}">
                            </div>
                            <div class="form-group mr-3 w-25">
                                <select name="manufacturer" class="form-control w-100">
                                    <option value="">メーカー名</option>
                                    @foreach($companies as $company)  <!-- 会社名をforeachで表示 -->
                                        <option value="{{ $company->name }}" {{ request()->get('manufacturer') == $company->name ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">検索</button>
                        </form>
                        <!-- 新規登録ボタン -->
                        <a href="{{ route('products.create') }}" class="btn btn-warning">新規登録</a>
                    </div>

                    <!-- 商品一覧テーブル -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>商品ID</th>
                                <th>商品画像</th>
                                <th>商品名</th>
                                <th>価格</th>
                                <th>在庫数</th>
                                <th>メーカー名</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td><img src="{{ asset('storage/'.$product->img_path) }}" alt="商品画像" width="50"></td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->price }}円</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->company ? $product->company->name : '未登録' }}</td> <!-- メーカー名を表示 -->
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細表示</a>
                                    <!-- 編集ボタンを削除 -->
                                    <!-- <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">編集</a> -->
                                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
                                    </form>
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
@endsection
