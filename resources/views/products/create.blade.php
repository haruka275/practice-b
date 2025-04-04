@extends('layouts.app')

@section('content')
<!-- 商品情報登録画面のスタイルシートを読み込む -->
<link href="{{ asset('css/create_product.css') }}" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ __('商品情報登録') }}</div>

                <div class="card-body">
                    <!-- 商品登録フォーム -->
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- 商品名 -->
                        <div class="form-group">
                            <label for="product_name">商品名*</label>
                            <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                            @error('product_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- メーカー -->
                        <div class="form-group">
                            <label for="company_id">メーカー*</label>
                            <select id="company_id" name="company_id" class="form-control" required>
                                <option value="">メーカーを選択してください</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 価格 -->
                        <div class="form-group">
                            <label for="price">価格*</label>
                            <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 在庫数 -->
                        <div class="form-group">
                            <label for="stock">在庫数*</label>
                            <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" required>
                            @error('stock')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- コメント -->
                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea id="comment" name="comment" class="form-control">{{ old('comment') }}</textarea>
                        </div>

                        <!-- 商品画像 -->
                        <div class="form-group">
                            <label for="img_path">商品画像</label>
                            <input type="file" id="img_path" name="img_path" class="form-control-file" accept="image/*">
                        </div>

                        <!-- ボタン -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-warning">登録</button>
                            <a href="{{ route('products.index') }}" class="btn btn-info">戻る</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
