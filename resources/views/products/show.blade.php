@extends('layouts.app')

@section('content')
<!-- 商品詳細画面のスタイルシートを読み込む -->
<link href="{{ asset('css/show_product.css') }}" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ __('商品情報詳細') }}</div>

                <div class="card-body">
                    <!-- 商品詳細表示 -->
                    <div class="form-group">
                        <label>商品情報ID</label>
                        <p>{{ $product->id }}</p>
                    </div>

                    <div class="form-group">
                        <label>商品画像</label>
                        @if($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid">
                        @else
                            <p>画像がありません</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>商品名</label>
                        <p>{{ $product->product_name }}</p>
                    </div>

                    <div class="form-group">
                        <label>メーカー</label>
                        <p>{{ $product->manufacturer }}</p>
                    </div>

                    <div class="form-group">
                        <label>価格</label>
                        <p>{{ number_format($product->price) }}円</p>
                    </div>

                    <div class="form-group">
                        <label>在庫数</label>
                        <p>{{ $product->stock }}</p>
                    </div>

                    <div class="form-group">
                        <label>コメント</label>
                        <p>{{ $product->comment ? $product->comment : 'コメントなし' }}</p>
                    </div>

                    <!-- ボタン -->
                    <div class="form-group text-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">更新</a>
                        <a href="{{ route('products.index') }}" class="btn btn-info">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
